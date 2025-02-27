<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Alg_Parcel_Factory.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Alg\Parcel;

use CeusMedia\Common\Alg\Parcel\Factory as ParcelFactory;
use CeusMedia\Common\Alg\Parcel\Packet as ParcelPacket;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\MockAntiProtection;
use Exception;

/**
 *	TestUnit of Alg_Parcel_Factory.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class FactoryTest extends BaseCase
{
	protected $articles;

	protected $packets;

	protected $volumes;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->articles	= array(
			'a',
			'b',
		);
		$this->packets	= array(
			'small',
			'large',
		);
		$this->volumes	= array(
			'small'	=> array(
				'a'	=> 0.3,
				'b'	=> 0.6,
			),
			'large'	=> array(
				'a'	=> 0.1,
				'b'	=> 0.25,
			),
		);
		$this->factory	= MockAntiProtection::getInstance( ParcelFactory::class, $this->packets, $this->articles, $this->volumes );
//		$this->factory	= new FactoryMockAntiProtection( $this->packets, $this->articles, $this->volumes );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
//		$factory	= new Alg_Parcel_FactoryInstance( $this->packets, $this->articles, $this->volumes );

		$assertion	= $this->packets;
		$creation	= $this->factory->getProtectedVar( 'packets' );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->articles;
		$creation	= $this->factory->getProtectedVar( 'articles' );
		self::assertEquals( $assertion, $creation );

		$assertion	= $this->volumes;
		$creation	= $this->factory->getProtectedVar( 'volumes' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduce()
	{
		$packet		= new ParcelPacket( 'large' );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'b', 0.25 );
		$packet->addArticle( 'b', 0.25 );

		$assertion	= $packet;
		$creation	= $this->factory->produce( 'large', array( 'a' => 3, 'b' => 2 ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException1()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->factory->produce( "not_existing", array( 'a' => 1 ) );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException2()
	{
		$this->expectException( 'InvalidArgumentException' );
		$this->factory->produce( "small", array( 'not_existing' => 1 ) );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException3()
	{
		$this->expectException( 'OutOfRangeException' );
		$this->factory->produce( "small", array( 'b' => 5 ) );
	}
}
class FactoryInstance extends ParcelFactory
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}

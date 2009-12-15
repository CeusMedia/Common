<?php
/**
 *	TestUnit of Alg_Parcel_Factory.
 *	@package		Tests.alg.parcel
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Alg_Parcel_Factory.
 *	@package		Tests.alg.parcel
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Parcel_Factory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.07.2008
 *	@version		0.1
 */
class Test_Alg_Parcel_FactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
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
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->factory	= new Alg_Parcel_Factory( $this->packets, $this->articles, $this->volumes );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$factory	= new Alg_Parcel_FactoryInstance( $this->packets, $this->articles, $this->volumes );
		
		$assertion	= $this->packets;
		$creation	= $factory->getProtectedVar( 'packets' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= $this->articles;
		$creation	= $factory->getProtectedVar( 'articles' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= $this->volumes;
		$creation	= $factory->getProtectedVar( 'volumes' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduce()
	{
		$packet		= new Alg_Parcel_Packet( 'large' );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'a', 0.1 );
		$packet->addArticle( 'b', 0.25 );
		$packet->addArticle( 'b', 0.25 );
		
		$assertion	= $packet;
		$creation	= $this->factory->produce( 'large', array( 'a' => 3, 'b' => 2 ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->factory->produce( "not_existing", array( 'a' => 1 ) );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->factory->produce( "small", array( 'not_existing' => 1 ) );
	}

	/**
	 *	Tests Exception of Method 'produce'.
	 *	@access		public
	 *	@return		void
	 */
	public function testProduceException3()
	{
		$this->setExpectedException( 'OutOfRangeException' );
		$this->factory->produce( "small", array( 'b' => 5 ) );
	}
}
class Alg_Parcel_FactoryInstance extends Alg_Parcel_Factory
{
	public function getProtectedVar( $varName )
	{
		if( !in_array( $varName, array_keys( get_object_vars( $this ) ) ) )
			throw new Exception( 'Var "'.$varName.'" is not declared.' );
		return $this->$varName;
	}
}
?>
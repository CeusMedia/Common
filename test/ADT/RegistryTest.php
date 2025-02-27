<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Registry
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

 namespace CeusMedia\CommonTest\ADT;

use CeusMedia\Common\ADT\Registry;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Registry
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RegistryTest extends BaseCase
{
	protected $registry;

	public function setUp(): void
	{
		$this->registry	= Registry::getInstance();
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$assertion	= "value1";
		$creation	= $this->registry->get( 'key1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStatic()
	{
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$assertion	= "value1";
		$creation	= Registry::getStatic( 'key1' );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'SetStatic'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetStatic()
	{
		$value	= 'value2';
		Registry::setStatic( 'key2', $value );
		self::assertEquals( 'value2', $GLOBALS['REFERENCES']['key2'] );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$GLOBALS['REFERENCES']['key1']	= "value1";

		$creation	= $this->registry->has( 'key1' );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$GLOBALS['REFERENCES']	= [];
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$this->registry->remove( 'key1' );

		$creation	= $this->registry->has( 'key1' );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		$data		= array( "value3" );
		$this->registry->set( 'key3', $data );
		$creation	= $GLOBALS['REFERENCES']['key3'];
		self::assertEquals( $data, $creation );
	}
}

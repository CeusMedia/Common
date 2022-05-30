<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Registry
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

 namespace CeusMedia\Common\Test;

use CeusMedia\Common\ADT\Registry;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Registry
 *	@package		Tests.ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class RegistryTest extends BaseCase
{
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'has'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHas()
	{
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$assertion	= true;
		$creation	= $this->registry->has( 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		$GLOBALS['REFERENCES']	= array();
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$this->registry->remove( 'key1' );
		$assertion	= false;
		$creation	= $this->registry->has( 'key1' );
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $data, $creation );
	}
}

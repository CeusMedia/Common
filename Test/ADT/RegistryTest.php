<?php
/**
 *	TestUnit of Registry
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Registry
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Registry
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_ADT_RegistryTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->registry	= ADT_Registry::getInstance();
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
		$creation	= ADT_Registry::getStatic( 'key1' );
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
?>
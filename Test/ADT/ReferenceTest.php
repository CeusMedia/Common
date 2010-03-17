<?php
/**
 *	TestUnit of Reference
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Reference
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Reference
 *	@package		Tests.adt
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			ADT_Reference
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_ADT_ReferenceTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
		$this->reference	= new ADT_Reference();
	}

	/**
	 *	Tests Method 'add'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAdd()
	{
		$data		= array( "value3" );
		$this->reference->add( 'key3', $data );
		$creation	= $GLOBALS['REFERENCES']['key3'];
		$this->assertEquals( $data, $creation );
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
		$creation	= $this->reference->get( 'key1' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$GLOBALS['REFERENCES']	= array();
		$GLOBALS['REFERENCES']['key1']	= "value1";
		$GLOBALS['REFERENCES']['key2']	= "value2";
		$assertion	= array( 'key1', 'key2' );
		$creation	= $this->reference->getList();
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
		$creation	= $this->reference->has( 'key1' );
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
		$this->reference->remove( 'key1' );
		$assertion	= false;
		$creation	= $this->reference->has( 'key1' );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
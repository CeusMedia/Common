<?php
/**
 *	TestUnit of XML DOM Storage.
 *	@package		Tests.xml.dom
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of XML DOM Storage.
 *	@package		Tests.xml.dom
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			XML_DOM_Storage
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.12.2007
 *	@version		0.1
 */
class Test_XML_DOM_StorageTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->fileName		= dirname( __FILE__ )."/storage.xml";
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->storage	= new XML_DOM_Storage( $this->fileName );
		$this->storage->set( "tests.test1.key1", "value11" );
		$this->storage->write();
	}

	/**
	 *	Sets up Leaf.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$assertion	= "value11";
		$creation	= $this->storage->get( "tests.test1.key1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemove()
	{
		//  remove Value
		$assertion	= true;
		$creation	= $this->storage->remove( "tests.test1.key1" );
		$this->assertEquals( $assertion, $creation );

		//  check Value
		$assertion	= NULL;
		$creation	= $this->storage->get( "tests.test1.key1" );
		$this->assertEquals( $assertion, $creation );

		//  try to remove Value again 
		$assertion	= false;
		$creation	= $this->storage->remove( "tests.test1.key1" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'remove'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveAndWrite()
	{
		//  remove Value and write
		$assertion	= true;
		$creation	= $this->storage->remove( "tests.test1.key1", true );
		$this->assertEquals( $assertion, $creation );

		//  remove Value and write
		$assertion	= 0;
		$creation	= substr_count( file_get_contents( $this->fileName ), "value11" );
		$this->assertEquals( $assertion, $creation );

		//  try to remove Value again 
		$assertion	= false;
		$creation	= $this->storage->remove( "tests.test1.key1", true );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSet()
	{
		//  set Value
		$assertion	= true;
		$creation	= $this->storage->set( "tests.test2.key1", "value21" );
		$this->assertEquals( $assertion, $creation );

		//  check Value
		$assertion	= "value21";
		$creation	= $this->storage->get( "tests.test2.key1" );
		$this->assertEquals( $assertion, $creation );

		//  try to set Value again 
		$assertion	= false;
		$creation	= $this->storage->set( "tests.test2.key1", "value21" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'set'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetAndWrite()
	{
		//  set Value and write
		$assertion	= true;
		$creation	= $this->storage->set( "tests.test2.key2", "value22", true );
		$this->assertEquals( $assertion, $creation );

		//  check Value in File
		$assertion	= 1;
		$creation	= substr_count( file_get_contents( $this->fileName ), "value22" );
		$this->assertEquals( $assertion, $creation );

		//  try to set Value again 
		$assertion	= false;
		$creation	= $this->storage->set( "tests.test2.key2", "value22", true );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
<?php
/**
 *	TestUnit of File_CSV_Iterator.
 *	@package		Tests.File.CSV
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_CSV_Iterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.08.2010
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Test/initLoaders.php5' );
import( 'File.CSV.Iterator' );
/**
 *	TestUnit of File_CSV_Iterator.
 *	@package		Tests.File.CSV
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_CSV_Iterator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.08.2010
 *	@version		0.1
 */
class Test_File_CSV_IteratorTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ ).'/';
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
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
	public function test__construct()
	{
		$mock		= Test_MockAntiProtection::getInstance( 'File_CSV_Iterator', $this->path.'read.csv', '|', '#' );

		$assertion	= TRUE;
		$creation	= is_object( $mock );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '|';
		$creation	= $mock->getProtectedVar( 'delimiter' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '#';
		$creation	= $mock->getProtectedVar( 'enclosure' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= is_resource( $mock->getProtectedVar( 'filePointer' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__constructException()
	{
		$this->setExpectedException( 'RuntimeException' );
		new File_CSV_Iterator( 'not_existing' );
	}

	/**
	 *	Tests Method 'rewind'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRewind()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'read.csv', ';' );
		$iterator->next();
		
		$assertion	= 1;
		$creation	= $iterator->key();
		$this->assertEquals( $assertion, $creation );

		$iterator->valid();
		$assertion	= array( '1', 'test1', 'string without semicolon' );
		$creation	= $iterator->current();
		$this->assertEquals( $assertion, $creation );

		$assertion	= NULL;
		$creation	= $iterator->rewind();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $iterator->key();
		$this->assertEquals( $assertion, $creation );

		$iterator->valid();
		$assertion	= array( 'id', 'col1', 'col2' );
		$creation	= $iterator->current();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'current'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCurrent()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'read.csv', ';' );
		$iterator->valid();

		$assertion	= array( 'id', 'col1', 'col2' );
		$creation	= $iterator->current();
		$this->assertEquals( $assertion, $creation );

		$iterator->valid();
		$assertion	= array( '1', 'test1', 'string without semicolon' );
		$creation	= $iterator->current();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'key'.
	 *	@access		public
	 *	@return		void
	 */
	public function testKey()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'read.csv', ';' );

		$assertion	= 0;
		$creation	= $iterator->key();
		$this->assertEquals( $assertion, $creation );

		$iterator->next();
		$assertion	= 1;
		$creation	= $iterator->key();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'next'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNext1()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'read.csv', ';' );

		$assertion	= TRUE;
		$creation	= $iterator->next();
		$this->assertEquals( $assertion, $creation );

		$iterator->next();
		$iterator->next();

		$assertion	= FALSE;
		$creation	= $iterator->next();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'next'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNext2()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'empty.csv', ';' );

		$assertion	= FALSE;
		$creation	= $iterator->next();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'valid'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValid1()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'read.csv', ';' );
		$assertion	= TRUE;
		$creation	= $iterator->valid();
		$this->assertEquals( $assertion, $creation );

		$creation	= $iterator->valid();
		$creation	= $iterator->valid();
		$assertion	= FALSE;
		$creation	= $iterator->valid();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'valid'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValid2()
	{
		$iterator	= new File_CSV_Iterator( $this->path.'empty.csv', ';' );
		$assertion	= FALSE;
		$creation	= $iterator->valid();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
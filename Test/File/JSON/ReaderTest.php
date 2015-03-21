<?php

/**
 *	TestUnit of File_JSON_Reader.
 *	@package		Tests.File.JSON
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_JSON_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.03.2015
 *	@version		0.1
 */
//require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Test/initLoaders.php5' );
/**
 *	TestUnit of File_JSON_Reader.
 *	@package		Tests.File.JSON
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_JSON_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.03.2015
 *	@version		0.1
 */
class Test_File_JSON_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ ).'/';

		$this->dataValid	= (object) array(
			'a'	=> "string",
			'b'	=> 1,
			'c'	=> array( 1, 2, 3 ),
			'd'	=> FALSE
		);
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
		$assertion	= "File_JSON_Reader";
		$creation	= get_class( new File_JSON_Reader( $this->path.'valid.json' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@expectedException		RuntimeException
	 */
	public function test__constructException()
	{
		$assertion	= TRUE;
		$creation	= new File_JSON_Reader( $this->path.'notexisting.json' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$assertion	= $this->dataValid;
		$creation	= File_JSON_Reader::load( $this->path.'valid.json' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{

//		$this->markTestIncomplete( 'Incomplete Test' );
		$filename	= dirname( __FILE__ ).'/valid.json';
		$reader		= new File_JSON_Reader( $filename );
		$assertion	= $this->dataValid;
		$creation	= $reader->read();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadEmpty(){
		$filename	= dirname( __FILE__ ).'/empty.json';
		$reader		= new File_JSON_Reader( $filename );
		$assertion	= NULL;
		$creation	= $reader->read();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	@expectedException		RuntimeException
	 *	@expectedExceptionCode	4
	 */
	public function testReadException(){
		$filename	= dirname( __FILE__ ).'/invalid.json';
		$reader		= new File_JSON_Reader( $filename );
		$assertion	= NULL;
		$creation	= $reader->read();
		$this->assertEquals( $assertion, $creation );
	}
}
?>

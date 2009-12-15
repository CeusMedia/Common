<?php
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Arc_Bzip
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Test_File_Arc_BzipTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;
	
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.bz";
	}
	
	public function setUp()
	{
	}
	
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	public function testWriteString()
	{
		$arc	= new File_Arc_Bzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= bzcompress( "test" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString()
	{
		$arc	= new File_Arc_Bzip( $this->fileName );
		$arc->writeString( "test" );
	
		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
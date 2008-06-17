<?php
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.file.arc.BzipFile' );
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			BzipFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_File_Arc_BzipFileTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;
	
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function setUp()
	{
		$this->fileName	= $this->path."test.bz";
	}
	
	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	public function testWriteString()
	{
		$arc	= new BzipFile( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString()
	{
		$arc	= new BzipFile( $this->fileName );
		$arc->writeString( "test" );
		$arc	= new BzipFile( $this->fileName );
	
		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
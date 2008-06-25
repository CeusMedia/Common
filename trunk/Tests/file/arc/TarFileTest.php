<?php
/**
 *	TestUnit of T File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			TarFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.file.arc.TarFile' );
/**
 *	TestUnit of Tar File.
 *	@package		Tests.file.arc
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			TarFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_File_Arc_TarFileTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;
	
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	public function setUp()
	{
		$this->fileName	= $this->path."test.tar";
	}

	public function tearDown()
	{
		@unlink( $this->fileName );
	}

	public function testAddFile()
	{
		$arc	= new TarFile();
		$arc->addFile( $this->path."AllTests.php" );
		$arc->addFile( $this->path."TarFileTest.php" );

		$assertion	= TRUE;
		$creation	= $arc->save( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
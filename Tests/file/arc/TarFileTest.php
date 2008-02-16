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
	
	public function setUp()
	{
		$this->fileName	= "Tests/file/arc/test.tar";
	}

	public function testAddFile()
	{
		$arc	= new TarFile();
		$arc->addFile( "Tests/file/arc/test.bz" );
		$arc->addFile( "Tests/file/arc/test.gz" );
		$creation	= $arc->save( $this->fileName );
		$assertion	= true;
		$this->assertEquals( $assertion, $creation );
	}
}
?>
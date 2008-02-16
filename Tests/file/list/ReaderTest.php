<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.file.list.Reader' );
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_File_List_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "Tests/file/list/read.list";
	
	public function testGetList()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$reader		= new File_List_Reader( $this->fileName );
		$creation	= $reader->getList();
		$this->assertEquals( $assertion, $creation );
	}

	public function testToArray()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$reader		= new File_List_Reader( $this->fileName );
		$creation	= $reader->toArray();
		$this->assertEquals( $assertion, $creation );
	}

	public function testRead()
	{
		$assertion	= array(
			"line1",
			"line2",
		);
		$creation	= File_List_Reader::read( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			YamlReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.file.list.SectionReader' );
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_SectionReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_List_SectionReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "file/list/section.read.list";
	private $sectionList	= array(
		"section1"	=> array(
			"line1",
			"line2",
		),
		"section2"	=> array(
			"line3",
			"line4",
		),
	);

	public function testRead()
	{
		$reader		= new File_List_SectionReader( $this->fileName );
		$creation	= $reader->read();
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testLoad()
	{
		$creation	= File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}
}
?>
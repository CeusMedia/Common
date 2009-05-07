<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_Reader
 *	@uses			File_List_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
import( 'de.ceus-media.file.list.SectionReader' );
import( 'de.ceus-media.file.list.SectionWriter' );
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_List_SectionReader
 *	@uses			File_List_SectionWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class File_List_SectionWriterTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "file/list/section.write.list";
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

	public function testWrite()
	{
		$writer		= new File_List_SectionWriter( $this->fileName );
		$writer->write( $this->sectionList );
		$creation	= File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testSave()
	{
		File_List_SectionWriter::save( $this->fileName, $this->sectionList );
		$creation	= File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}
}
?>
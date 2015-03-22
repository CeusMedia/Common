<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		Test_Case
 *	@uses			File_List_SectionReader
 *	@uses			File_List_SectionWriter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_File_List_SectionWriterTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;
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

	public function __construct()
	{
		$this->fileName		= dirname( __FILE__ )."/section.write.list";
	}

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

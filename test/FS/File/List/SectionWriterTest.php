<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@extends		Test_Case
 *	@uses			FS_File_List_SectionReader
 *	@uses			FS_File_List_SectionWriter
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_List_SectionWriterTest extends Test_Case
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

	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/section.write.list";
	}

	public function testWrite()
	{
		$writer		= new FS_File_List_SectionWriter( $this->fileName );
		$writer->write( $this->sectionList );
		$creation	= FS_File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testSave()
	{
		FS_File_List_SectionWriter::save( $this->fileName, $this->sectionList );
		$creation	= FS_File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}
}

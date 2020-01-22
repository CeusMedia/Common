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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_List_SectionReaderTest extends Test_Case
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

	public function setUp()
	{
		$this->fileName		= dirname( __FILE__ )."/section.read.list";
	}

	public function testRead()
	{
		$reader		= new FS_File_List_SectionReader( $this->fileName );
		$creation	= $reader->read();
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testLoad()
	{
		$creation	= FS_File_List_SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}
}

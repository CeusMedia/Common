<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.list
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_FS_File_List_SectionWriterTest extends BaseCase
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

<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Collection Section Writer.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Collection;

use CeusMedia\Common\FS\File\Collection\SectionReader;
use CeusMedia\Common\FS\File\Collection\SectionWriter;
use CeusMedia\CommonTest\BaseCase;

/**
*	TestUnit of Collection Section Writer.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SectionWriterTest extends BaseCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private string $fileName;

	private array $sectionList	= array(
		"section1"	=> array(
			"line1",
			"line2",
		),
		"section2"	=> array(
			"line3",
			"line4",
		),
	);

	public function testWrite(): void
	{
		$writer		= new SectionWriter( $this->fileName );
		$writer->write( $this->sectionList );
		$creation	= SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testSave(): void
	{
		SectionWriter::save( $this->fileName, $this->sectionList );
		$creation	= SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}

	protected function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/section.write.list";
	}
}

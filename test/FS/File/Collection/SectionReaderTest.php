<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Collection Section Reader.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Collection;

use CeusMedia\Common\FS\File\Collection\SectionReader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Collection Section Reader.
 *	@package		Tests.FS.File.Collection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class SectionReaderTest extends BaseCase
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

	public function testRead(): void
	{
		$reader		= new SectionReader( $this->fileName );
		$creation	= $reader->read();
		self::assertEquals( $this->sectionList, $creation );
	}

	public function testLoad(): void
	{
		$creation	= SectionReader::load( $this->fileName );
		self::assertEquals( $this->sectionList, $creation );
	}

	protected function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/section.read.list";
	}
}

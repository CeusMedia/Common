<?php
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
		$this->fileName		= dirname( __FILE__ )."/section.read.list";
	}

	public function testRead()
	{
		$reader		= new SectionReader( $this->fileName );
		$creation	= $reader->read();
		$this->assertEquals( $this->sectionList, $creation );
	}

	public function testLoad()
	{
		$creation	= SectionReader::load( $this->fileName );
		$this->assertEquals( $this->sectionList, $creation );
	}
}

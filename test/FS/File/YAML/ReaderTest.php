<?php
declare( strict_types = 1 );
/**
 *	TestUnit of YAML Reader.
 *	@package		Test.FS.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */

namespace CeusMedia\CommonTest\FS\File\YAML;

use CeusMedia\Common\FS\File\YAML\Reader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of YAML Reader.
 *	@package		Test.FS.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class ReaderTest extends BaseCase
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->fileName		= dirname( __FILE__ )."/reader.yaml";
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$creation	= Reader::load( $this->fileName );
		$assertion	= array(
			"title" => "test",
			"list"	=> array(
				"entry1",
				"entry2",
				)
			);
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$reader		= new Reader( $this->fileName );
		$creation	= $reader->read();
		$assertion	= array(
			"title" => "test",
			"list"	=> array(
				"entry1",
				"entry2",
				)
			);
		self::assertEquals( $assertion, $creation );
	}
}

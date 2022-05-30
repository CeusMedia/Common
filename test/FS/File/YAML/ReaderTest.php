<?php
/**
 *	TestUnit of YAML Reader.
 *	@package		Test.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of YAML Reader.
 *	@package		Test.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class Test_FS_File_YAML_ReaderTest extends BaseCase
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
		$creation	= FS_File_YAML_Reader::load( $this->fileName );
		$assertion	= array(
			"title" => "test",
			"list"	=> array(
				"entry1",
				"entry2",
				)
			);
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$reader		= new FS_File_YAML_Reader( $this->fileName );
		$creation	= $reader->read();
		$assertion	= array(
			"title" => "test",
			"list"	=> array(
				"entry1",
				"entry2",
				)
			);
		$this->assertEquals( $assertion, $creation );
	}
}

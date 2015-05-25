<?php
/**
 *	TestUnit of YAML Reader.
 *	@package		Test.File.YAML
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of YAML Reader.
 *	@package		Test.File.YAML
 *	@extends		Test_Case
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_File_YAML_ReaderTest extends Test_Case
{
	/**	@var	string		$fileName		File Name of Test File */
	private $fileName;

	public function __construct()
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
		$creation	= File_YAML_Reader::load( $this->fileName );
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
		$reader		= new File_YAML_Reader( $this->fileName );
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
?>

<?php
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			YamlReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.yaml.Reader' );
/**
 *	TestUnit of Yaml Reader.
 *	@package		Tests.file.yaml
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Tests_File_Yaml_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName		= "Tests/file/yaml/reader.yaml";

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$creation	= File_Yaml_Reader::load( $this->fileName );
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
		$reader		= new File_Yaml_Reader( $this->fileName );
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
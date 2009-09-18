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
	private $fileName		= "";

	public function __construct()
	{
		$this->path			= dirname( __FILE__ )."/";
		$this->fileName		= $this->path."spyc.yaml";
		$this->data			= unserialize( file_get_contents( $this->path."data.serial" ) );
	}

	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$creation	= File_Yaml_Reader::load( $this->fileName );
#		file_put_contents( $this->path."data.serial", serialize( $creation ) );
		$this->assertEquals( $this->data, $creation );
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
		$this->assertEquals( $this->data, $creation );
	}
}
?>
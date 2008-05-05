<?php
/**
 *	TestUnit of Service_Definition_XmlReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.service.definition/XmlReader' );
import( 'de.ceus-media.file.yaml.Reader' );
/**
 *	TestUnit of Service_Definition_XmlReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Service_Definition_XmlReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."services.xml";
	}
	
	/**
	 *	Tests Method 'load'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoad()
	{
		$assertion	= File_YAML_Reader::load( $this->path."services.yaml" );
		$creation	= Service_Definition_XmlReader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
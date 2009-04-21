<?php
/**
 *	TestUnit of Net Service Definition XmlReader.
 *	@package		Tests.net.service.definition
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Definition_XmlReader
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.service.definition.Loader' );
import( 'de.ceus-media.net.service.definition.XmlReader' );
import( 'de.ceus-media.file.yaml.Reader' );
/**
 *	TestUnit of Net Service Definition XmlReader.
 *	@package		Tests.net.service.definition
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Definition_XmlReader
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Net_Service_Definition_XmlReaderTest extends PHPUnit_Framework_TestCase
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
		$reader		= new Net_Service_Definition_Loader;
		$assertion	= $reader->loadServices( $this->path."services.yaml" );
		$creation	= Net_Service_Definition_XmlReader::load( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
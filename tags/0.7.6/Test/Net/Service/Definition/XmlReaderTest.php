<?php
/**
 *	TestUnit of Net Service Definition XmlReader.
 *	@package		Tests.net.service.definition
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Net Service Definition XmlReader.
 *	@package		Tests.net.service.definition
 *	@extends		Test_Case
 *	@uses			Net_Service_Definition_XmlReader
 *	@uses			File_YAML_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_Definition_XmlReaderTest extends Test_Case
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

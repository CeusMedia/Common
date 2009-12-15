<?php
/**
 *	TestUnit of Net Service Definition Loader.
 *	@package		Tests.net.service.definition
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'test/initLoaders.php5';
/**
 *	TestUnit of Net Service Definition Loader.
 *	@package		Tests.net.service.definition
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Definition_Loader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_Definition_LoaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->data		= Net_Service_Definition_XmlReader::load( $this->path."services.xml" );
		$this->loader	= new Net_Service_Definition_Loader;
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		@unlink( $this->path."services.json.cache" );
		@unlink( $this->path."services.xml.cache" );
		@unlink( $this->path."services.yaml.cache" );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->path."services.json.cache" );
		@unlink( $this->path."services.xml.cache" );
		@unlink( $this->path."services.yaml.cache" );
	}

	/**
	 *	Tests Method 'loadServices' with JSON Service Definition.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadServicesWithJson()
	{
		$assertion	= $this->data;	
		$creation	= $this->loader->loadServices( $this->path."services.json", $this->path."services.json.cache" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadServices' with YAML Service Definition.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadServicesWithYaml()
	{
		$assertion	= $this->data;	
		$creation	= $this->loader->loadServices( $this->path."services.yaml", $this->path."services.yaml.cache" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'loadServices' with XML Service Definition.
	 *	@access		public
	 *	@return		void
	 */
	public function testLoadServicesWithXml()
	{
		$assertion	= $this->data;	
		$creation	= $this->loader->loadServices( $this->path."services.xml", $this->path."services.xml.cache" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
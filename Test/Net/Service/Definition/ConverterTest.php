<?php
/**
 *	TestUnit of Net Service Definition Converter.
 *	@package		Tests.net.service.definition
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Net Service Definition Converter.
 *	@package		Tests.net.service.definition
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Service_Definition_Converter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Net_Service_Definition_ConverterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path			= dirname( __FILE__ )."/";
		$this->fileJson		= $this->path."services.json";
		$this->fileXml		= $this->path."services.xml";
		$this->fileYaml		= $this->path."services.yaml";
		$this->fileTemp		= $this->path."services.temp";
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */ 
	public function tearDown()
	{
		@unlink( $this->fileTemp );
	}
	
	/**
	 *	Tests Method 'convertJsonFileToXmlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertJsonFileToXmlFile()
	{
		Net_Service_Definition_Converter::convertJsonFileToXmlFile( $this->fileJson, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileXml );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertJsonFileToYamlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertJsonFileToYamlFile()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		Net_Service_Definition_Converter::convertJsonFileToYamlFile( $this->fileJson, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileYaml );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertXmlFileToJsonFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertXmlFileToJsonFile()
	{
		Net_Service_Definition_Converter::convertXmlFileToJsonFile( $this->fileXml, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileJson );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertXmlFileToYamlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertXmlFileToYamlFile()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		Net_Service_Definition_Converter::convertXmlFileToYamlFile( $this->fileXml, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileYaml );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertYamlFileToJsonFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertYamlFileToJsonFile()
	{
		Net_Service_Definition_Converter::convertYamlFileToJsonFile( $this->fileYaml, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileJson );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertYamlFileToXmlFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertYamlFileToXmlFile()
	{
		Net_Service_Definition_Converter::convertYamlFileToXmlFile( $this->fileYaml, $this->fileTemp );
		$assertion	= file_get_contents( $this->fileXml );
		$creation	= file_get_contents( $this->fileTemp );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
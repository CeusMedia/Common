<?php
/**
 *	TestUnit of File_Configuration_Converter.
 *	@package		Tests.file.configuration
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Configuration_Converter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.file.configuration.Converter' );
/**
 *	TestUnit of File_Configuration_Converter.
 *	@package		Tests.file.configuration
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Configuration_Converter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			08.05.2008
 *	@version		0.1
 */
class File_Configuration_ConverterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->path	= dirname( __FILE__ )."/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->path."test.ini.json" );
		@unlink( $this->path."test.ini.xml" );
		@unlink( $this->path."test.json.ini" );
		@unlink( $this->path."test.json.xml" );
		@unlink( $this->path."test.xml.ini" );
		@unlink( $this->path."test.xml.json" );
	}

	/**
	 *	Tests Method 'convertIniToJson'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertIniToJson()
	{
		$sourceFile	= $this->path."test.ini";
		$targetFile	= $this->path."test.ini.json";
		$assertFile	= $this->path."test.json";

		$length		= File_Configuration_Converter::convertIniToJson( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertIniToXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertIniToXml()
	{
		$sourceFile	= $this->path."test.ini";
		$targetFile	= $this->path."test.ini.xml";
		$assertFile	= $this->path."test.xml";

		$length		= File_Configuration_Converter::convertIniToXml( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertJsonToIni'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertJsonToIni()
	{
		$sourceFile	= $this->path."test.json";
		$targetFile	= $this->path."test.json.ini";
		$assertFile	= $this->path."test.ini";

		$length		= File_Configuration_Converter::convertJsonToIni( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertJsonToXml'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertJsonToXml()
	{
		$sourceFile	= $this->path."test.json";
		$targetFile	= $this->path."test.json.xml";
		$assertFile	= $this->path."test.xml";

		$length		= File_Configuration_Converter::convertJsonToXml( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertXmlToIni'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertXmlToIni()
	{
		$sourceFile	= $this->path."test.xml";
		$targetFile	= $this->path."test.xml.ini";
		$assertFile	= $this->path."test.ini";

		$length		= File_Configuration_Converter::convertXmlToIni( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertXmlToJson'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertXmlToJson()
	{
		$sourceFile	= $this->path."test.xml";
		$targetFile	= $this->path."test.xml.json";
		$assertFile	= $this->path."test.json";

		$length		= File_Configuration_Converter::convertXmlToJson( $sourceFile, $targetFile );

		$assertion	= TRUE;
		$creation	= is_int( $length );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= TRUE;
		$creation	= $length > 0;
		$this->assertEquals( $assertion, $creation );		

		$assertion	= file_get_contents( $assertFile );
		$creation	= file_get_contents( $targetFile );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
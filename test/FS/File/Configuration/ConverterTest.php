<?php
/**
 *	TestUnit of FS_File_Configuration_Converter.
 *	@package		Tests.file.configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of FS_File_Configuration_Converter.
 *	@package		Tests.file.configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			08.05.2008
 */
class Test_FS_File_Configuration_ConverterTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/";
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
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

		$length		= FS_File_Configuration_Converter::convertIniToJson( $sourceFile, $targetFile );

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

		$length		= FS_File_Configuration_Converter::convertIniToXml( $sourceFile, $targetFile );

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

		$length		= FS_File_Configuration_Converter::convertJsonToIni( $sourceFile, $targetFile );

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

		$length		= FS_File_Configuration_Converter::convertJsonToXml( $sourceFile, $targetFile );

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

		$length		= FS_File_Configuration_Converter::convertXmlToIni( $sourceFile, $targetFile );

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

		$length		= FS_File_Configuration_Converter::convertXmlToJson( $sourceFile, $targetFile );

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

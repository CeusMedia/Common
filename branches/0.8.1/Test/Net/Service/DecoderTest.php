<?php
/**
 *	TestUnit of Net_Service_Decoder.
 *	@package		Tests.net.service
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.04.2009
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of Net_Service_Decoder.
 *	@package		Tests.net.service
 *	@extends		Test_Case
 *	@uses			Net_Service_Decoder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			06.04.2009
 *	@version		0.1
 */
class Test_Net_Service_DecoderTest extends Test_Case
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->decoder	= new Net_Service_Decoder();
		$this->path		= dirname( __FILE__ )."/";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'Missing cURL support' );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseJson()
	{
		$json	=	'{ "status": "data", "data": "2008-12-31T12:34:56+02:00", "timestamp": 1239028369, "duration": 217 }';
		$assertion	= "2008-12-31T12:34:56+02:00";
		$creation	= $this->decoder->decodeResponse( $json, "json" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponsePhp()
	{
		$php		= 'a:4:{s:6:"status";s:4:"data";s:4:"data";s:25:"2008-12-31T12:34:56+02:00";s:9:"timestamp";i:1239028760;s:8:"duration";d:410;}';
		$assertion	= "2008-12-31T12:34:56+02:00";
		$creation	= $this->decoder->decodeResponse( $php, "php" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseTxt()
	{
		$txt		= "2008-12-31T12:34:56+02:00";
		$assertion	= "2008-12-31T12:34:56+02:00";
		$creation	= $this->decoder->decodeResponse( $txt, "txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseWddx()
	{
		if( !function_exists( 'wddx_packet_start' ) )
			$this->markTestSkipped( 'Missing WDDX support' );
		$wddx		= '<?xml version="1.0"?> <wddxPacket version="1.0">  <header/>  <data>  <struct>  <var name="status">  <string>data</string>  </var>  <var name="data">  <string>2008-12-31T12:34:56+02:00</string>  </var>  <var name="timestamp">  <number>1239028698</number>  </var>  <var name="duration">  <number>211</number>  </var>  </struct>  </data> </wddxPacket>';
		$assertion	= "2008-12-31T12:34:56+02:00";
		$creation	= $this->decoder->decodeResponse( $wddx, "wddx");
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseXml()
	{
		$xml		= '<?xml version="1.0"?> <response>  <status>data</status>  <data>2008-12-31T12:34:56+02:00</data>  <timestamp>1239028578</timestamp>  <duration>244</duration> </response>';
		$assertion	= new SimpleXMLElement( '<?xml version="1.0"?><root>2008-12-31T12:34:56+02:00</root>' );
		$creation	= $this->decoder->decodeResponse( $xml, "xml" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionJson1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$json	= file_get_contents( $this->path."responseException.json" );
		$this->decoder->decodeResponse( $json, "json" );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionJson2()
	{
		$json		= file_get_contents( $this->path."responseException.json" );
		try
		{
			$this->decoder->decodeResponse( $json, "json" );
		}
		catch( Exception $e )
		{
			$assertion	= "Test Exception (responded)";
			$creation	= $e->getMessage();
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionPhp1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$serial	= file_get_contents( $this->path."responseException.serial" );
		$this->decoder->decodeResponse( $serial, "php" );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionPhp2()
	{
		$serial	= file_get_contents( $this->path."responseException.serial" );
		try
		{
			$this->decoder->decodeResponse( $serial, "php" );
		}
		catch( Exception $e )
		{
			$assertion	= "Test Exception (responded)";
			$creation	= $e->getMessage();
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionTxt()
	{
		$response	= "Test Exception (responded)";
		$assertion	= "Test Exception (responded)";
		$creation	= $this->decoder->decodeResponse( $response, "txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionWddx1()
	{
		if( !function_exists( 'wddx_packet_start' ) )
			$this->markTestSkipped( 'Missing WDDX support' );
		$this->setExpectedException( 'RuntimeException' );
		$wddx	= file_get_contents( $this->path."responseException.wddx" );
		$this->decoder->decodeResponse( $wddx, "wddx" );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionWddx2()
	{
		if( !function_exists( 'wddx_packet_start' ) )
			$this->markTestSkipped( 'Missing WDDX support' );
		$wddx	= file_get_contents( $this->path."responseException.wddx" );
		try
		{
			$this->decoder->decodeResponse( $wddx, "wddx" );
		}
		catch( Exception $e )
		{
			$assertion	= "Test Exception (responded)";
			$creation	= $e->getMessage();
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionXml1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$xml		= file_get_contents( $this->path."responseException.xml" );
		$creation	= $this->decoder->decodeResponse( $xml, "xml" );
	}

	/**
	 *	Tests Method 'decodeResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecodeResponseExceptionXml2()
	{
		$xml		= file_get_contents( $this->path."responseException.xml" );
		try
		{
			$this->decoder->decodeResponse( $xml, "xml" );
		}
		catch( Exception $e )
		{
			$assertion	= "Test Exception (responded)";
			$creation	= $e->getMessage();
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'decompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecompressResponseDeflate()
	{
		if( !function_exists( 'gzcompress' ) )
			$this->markTestSkipped( 'Missing gzip support' );
		$message	= "this is a uncompressed test text.";
		$creation	= $this->decoder->decompressResponse( gzcompress( $message ), "deflate" );
		$this->assertEquals( $message, $creation );
	}

	/**
	 *	Tests Method 'decompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecompressResponseDeflateException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->decoder->decompressResponse( "this is a uncompressed test text.", "deflate" );
	}

	/**
	 *	Tests Method 'decompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecompressResponseGzip()
	{
		if( !function_exists( 'gzencode' ) )
			$this->markTestSkipped( 'Missing gzip support' );
		$message	= "this is a uncompressed test text.";
		$creation	= $this->decoder->decompressResponse( gzencode( $message ), "gzip" );
		$this->assertEquals( $message, $creation );
	}

	/**
	 *	Tests Method 'decompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecompressResponseGzipException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->decoder->decompressResponse( "this is a uncompressed test text.", "gzip" );
	}
	
	/**
	 *	Tests Method 'decompressResponse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDecompressResponseException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->decoder->decompressResponse( "", "not_supported" );
	}
}
?>

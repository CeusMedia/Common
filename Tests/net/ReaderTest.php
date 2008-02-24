<?php
/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net.http
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.Reader' );
/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net.http
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
class Tests_Net_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Tests Method 'getUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetUrl()
	{
		$url		= "http://www.example.com";
		$needle		= "@RFC\s+2606@i";
		
		$url		= "http://ceus-media.de/";
		$needle		= "@ceus media@i";

		$reader		= new Net_Reader( $url );
		$assertion	= $url;
		$creation	= $reader->getUrl();
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$url		= "http://www.example.com";
		$needle		= "@RFC\s+2606@i";
		
		$url		= "http://ceus-media.de/";
		$needle		= "@ceus media@i";

		$reader		= new Net_Reader( $url );
		$response	= $reader->read();
		$assertion	= true;
		$creation	= (bool) preg_match( $needle, $response );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$url		= "http://www.example.com";
		$needle		= "@RFC\s+2606@i";
		
		$url		= "http://ceus-media.de/";
		$needle		= "@ceus media@i";

		$response	= Net_Reader::readUrl( $url );
		$assertion	= true;
		$creation	= (bool) preg_match( $needle, $response );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUserAgent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUserAgent()
	{
		$url		= "http://www.example.com";
		$needle		= "@RFC\s+2606@i";
		
		$url		= "http://ceus-media.de/";
		$needle		= "@ceus media@i";

		$reader		= new Net_Reader( $url );
		$reader->setUserAgent( "UnitTest" );
		
		$assertion	= "UnitTest";
		$creation	= $reader->getUserAgent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUrl()
	{
		$url		= "http://www.example.com";
		$needle		= "@RFC\s+2606@i";
		
		$url		= "http://ceus-media.de/";
		$needle		= "@ceus media@i";

		$reader		= new Net_Reader( $url );
		$reader->setUrl( "test.com" );
		
		$assertion	= "test.com";
		$creation	= $reader->getUrl();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
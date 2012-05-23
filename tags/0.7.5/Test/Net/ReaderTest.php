<?php
/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
class Test_Net_ReaderTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->url		= "http://www.example.com";
		$this->needle	= "@RFC\s+2606@i";
		
		$this->url		= "http://ceusmedia.de/";
		$this->needle	= "@ceus media@i";
	}

	/**
	 *	Sets up Reader.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->reader	= new Net_Reader( $this->url );
		$this->reader->setUserAgent( "cmClasses:UnitTest/0.1" );
	}

	/**
	 *	Tests Method 'getInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInfo()
	{
		$response	= $this->reader->read();
		$assertion	= "200";
		$creation	= $this->reader->getInfo( Net_CURL::INFO_HTTP_CODE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= true;
		$creation	= (bool) count( $this->reader->getInfo() );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInfoException1()
	{
		$this->setExpectedException( "RuntimeException" );
		$this->reader->getInfo();
	}

	/**
	 *	Tests Method 'getInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInfoException2()
	{
		$this->reader->read();
		$this->setExpectedException( "InvalidArgumentException" );
		var_dump( $this->reader->getInfo( "invalid_key" ) );
	}

	/**
	 *	Tests Method 'getUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetUrl()
	{
		$assertion	= $this->url;
		$creation	= $this->reader->getUrl();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$response	= $this->reader->read();
		$assertion	= true;
		$creation	= (bool) preg_match( $this->needle, $response );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->setExpectedException( "RuntimeException" );
		$reader		= new Net_Reader( "" );
		$reader->read();
	}
	
	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$response	= Net_Reader::readUrl( $this->url );
		$assertion	= true;
		$creation	= (bool) preg_match( $this->needle, $response );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUserAgent'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUserAgent()
	{
		$this->reader->setUserAgent( "UnitTest1" );
		
		$assertion	= "UnitTest1";
		$creation	= $this->reader->getUserAgent();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUrl()
	{
		$this->reader->setUrl( "test.com" );
		
		$assertion	= "test.com";
		$creation	= $this->reader->getUrl();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetUrlException()
	{
		$this->setExpectedException( "InvalidArgumentException" );
		$this->reader->setUrl( "" );
	}
}
?>
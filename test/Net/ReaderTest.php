<?php
/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.02.2008
 *
 */
declare( strict_types = 1 );

use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			21.02.2008
 *
 */
class Test_Net_ReaderTest extends BaseCase
{
	/**
	 *	Sets up Reader.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->url		= "http://www.example.com";
		$this->needle	= "@RFC\s+2606@i";

		$this->url		= "http://ceusmedia.de/";
		$this->needle	= "@ceus media@i";

		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'Missing cURL support' );
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
		$this->expectException( "RuntimeException" );
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
		$this->expectException( "InvalidArgumentException" );
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
		$this->expectException( "RuntimeException" );
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
		$this->expectException( "InvalidArgumentException" );
		$this->reader->setUrl( "" );
	}
}

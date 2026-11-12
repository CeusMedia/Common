<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net;

use CeusMedia\Common\Net\CURL as NetCURL;
use CeusMedia\Common\Net\Reader as NetReader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net Reader.
 *	@package		Tests.net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *
 */
class ReaderTest extends BaseCase
{
	protected $url;
	protected $needle;

	/** @var NetReader  */
	protected $reader;

	/**
	 *	Sets up Reader.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
//		$this->url		= "https://www.example.com";
//		$this->needle	= "@RFC\s+2606@i";

		$this->url		= "https://ceusmedia.de/";
		$this->needle	= "@ceus media@i";

		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'Missing cURL support' );
		$this->reader	= new NetReader( $this->url );
		$this->reader->setUserAgent( "cmClasses:UnitTest/0.1" );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructException()
	{
		$this->expectException( "InvalidArgumentException" );
		$reader		= new NetReader( "" );
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
		$creation	= $this->reader->getInfo( NetCURL::INFO_HTTP_CODE );
		self::assertEquals( $assertion, $creation );

		$creation	= (bool) count( $this->reader->getInfo() );
		self::assertTrue( $creation );
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
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRead()
	{
		$response	= $this->reader->read();

		$creation	= (bool) preg_match( $this->needle, $response );
		self::assertTrue( $creation );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->expectException( "InvalidArgumentException" );
		$reader		= new NetReader( "invalidUrl" );
		$reader->read();
	}

	/**
	 *	Tests Method 'readUrl'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadUrl()
	{
		$response	= NetReader::readUrl( $this->url );

		$creation	= (bool) preg_match( $this->needle, $response );
		self::assertTrue( $creation );
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
		self::assertEquals( $assertion, $creation );
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
		self::assertEquals( $assertion, $creation );
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

	/**
	 *	Tests defaults: TLS verification is enabled and timeouts are not set (until 1.2).
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateCurlDefaults()
	{
		$curl	= $this->createExposedReader()->exposeCreateCurl();

		self::assertSame( 2, $curl->getOption( CURLOPT_SSL_VERIFYHOST ) );
		self::assertTrue( $curl->getOption( CURLOPT_SSL_VERIFYPEER ) );
		self::assertNull( $curl->getOption( CURLOPT_TIMEOUT ) );
		self::assertNull( $curl->getOption( CURLOPT_CONNECTTIMEOUT ) );
		self::assertSame( CURLPROTO_HTTP | CURLPROTO_HTTPS, $curl->getOption( CURLOPT_PROTOCOLS ) );
	}

	/**
	 *	Tests that TLS verification can be enabled and disabled explicitly.
	 *	CURLOPT_SSL_VERIFYHOST must be set as integer 2, not as boolean.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateCurlWithVerification()
	{
		$reader	= $this->createExposedReader();
		$reader->setVerifyHost( FALSE )->setVerifyPeer( FALSE );
		$curl	= $reader->exposeCreateCurl();
		self::assertSame( 0, $curl->getOption( CURLOPT_SSL_VERIFYHOST ) );
		self::assertFalse( $curl->getOption( CURLOPT_SSL_VERIFYPEER ) );

		$reader->setVerifyHost( TRUE )->setVerifyPeer( TRUE );
		$curl	= $reader->exposeCreateCurl();
		self::assertSame( 2, $curl->getOption( CURLOPT_SSL_VERIFYHOST ) );
		self::assertTrue( $curl->getOption( CURLOPT_SSL_VERIFYPEER ) );
	}

	/**
	 *	Tests that a global timeout set by Net\CURL::setTimeOut() still applies, if no timeout is set on the reader.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateCurlKeepsGlobalTimeout()
	{
		\CeusMedia\Common\Net\CURL::setTimeOut( 15 );
		try{
			$curl	= $this->createExposedReader()->exposeCreateCurl();
			self::assertSame( 15, $curl->getOption( CURLOPT_TIMEOUT ) );
			self::assertSame( 15, $curl->getOption( CURLOPT_CONNECTTIMEOUT ) );

			$curl	= $this->createExposedReader()->setTimeout( 5 )->exposeCreateCurl();
			self::assertSame( 5, $curl->getOption( CURLOPT_TIMEOUT ) );
		}
		finally{
			\CeusMedia\Common\Net\CURL::setTimeOut( 0 );
		}
	}

	/**
	 *	Tests Methods 'setTimeout' and 'setConnectTimeout' and overriding by given cURL options.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateCurlTimeouts()
	{
		$reader	= $this->createExposedReader();
		$reader->setTimeout( 5 )->setConnectTimeout( 2 );
		$curl	= $reader->exposeCreateCurl();
		self::assertSame( 5, $curl->getOption( CURLOPT_TIMEOUT ) );
		self::assertSame( 2, $curl->getOption( CURLOPT_CONNECTTIMEOUT ) );

		$reader->setTimeout( 0 )->setConnectTimeout( -1 );
		$curl	= $reader->exposeCreateCurl();
		self::assertNull( $curl->getOption( CURLOPT_TIMEOUT ) );
		self::assertNull( $curl->getOption( CURLOPT_CONNECTTIMEOUT ) );

		$curl	= $reader->exposeCreateCurl( ['CURLOPT_TIMEOUT' => 7] );
		self::assertSame( 7, $curl->getOption( CURLOPT_TIMEOUT ) );
	}

	/**
	 *	Tests default user agent.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefaultUserAgent()
	{
		$property	= new \ReflectionProperty( NetReader::class, 'userAgent' );
		self::assertSame( 'CeusMedia-Common/1.1 Net\\Reader', $property->getDefaultValue() );
	}

	/**
	 *	Tests that local files cannot be read.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadFileUrlException()
	{
		$reader		= new NetReader( 'file://localhost'.__FILE__ );
		$this->expectException( "RuntimeException" );
		$reader->read();
	}

	protected function createExposedReader(): NetReader
	{
		return new class( 'https://localhost/' ) extends NetReader
		{
			public function exposeCreateCurl( array $curlOptions = [] ): NetCURL
			{
				return $this->createCurl( $curlOptions );
			}
		};
	}
}

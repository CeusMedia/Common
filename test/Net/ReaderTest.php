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
	 *	Tests Method 'getInfo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInfo()
	{
		$response	= $this->reader->read();
		$assertion	= "200";
		$creation	= $this->reader->getInfo( NetCURL::INFO_HTTP_CODE );
		$this->assertEquals( $assertion, $creation );

		$creation	= (bool) count( $this->reader->getInfo() );
		$this->assertTrue( $creation );
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

		$creation	= (bool) preg_match( $this->needle, $response );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Exception of Method 'read'.
	 *	@access		public
	 *	@return		void
	 */
	public function testReadException()
	{
		$this->expectException( "InvalidArgumentException" );
		$reader		= new NetReader( "" );
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
		$this->assertTrue( $creation );
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

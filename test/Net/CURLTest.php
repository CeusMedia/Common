<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_CURL.
 *	@package		Tests.Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net;

use CeusMedia\Common\Net\CURL as Net_CURL;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_CURL.
 *	@package		Tests.Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class CURLTest extends BaseCase
{
	/** @var resource|NULL */
	protected static $process		= NULL;

	protected static string $url	= '';

	public static function setUpBeforeClass(): void
	{
		if( !extension_loaded( 'curl' ) || !function_exists( 'proc_open' ) )
			return;
		$socket	= @stream_socket_server( 'tcp://127.0.0.1:0' );
		if( FALSE === $socket )
			return;
		$address	= (string) stream_socket_get_name( $socket, FALSE );
		fclose( $socket );
		$port		= (int) substr( $address, strrpos( $address, ':' ) + 1 );

		$command	= [PHP_BINARY, '-S', '127.0.0.1:'.$port, __DIR__.'/assets/curlServer.php'];
		$pipes		= [];
		$process	= proc_open( $command, [1 => ['file', '/dev/null', 'w'], 2 => ['file', '/dev/null', 'w']], $pipes );
		if( !is_resource( $process ) )
			return;
		for( $i = 0; $i < 50; $i++ ){
			$connection	= @fsockopen( '127.0.0.1', $port, $errorCode, $errorMessage, 0.1 );
			if( FALSE !== $connection ){
				fclose( $connection );
				self::$process	= $process;
				self::$url		= 'http://127.0.0.1:'.$port.'/';
				return;
			}
			usleep( 100_000 );
		}
		proc_terminate( $process );
		proc_close( $process );
	}

	public static function tearDownAfterClass(): void
	{
		if( is_resource( self::$process ) ){
			proc_terminate( self::$process );
			proc_close( self::$process );
		}
		self::$process	= NULL;
	}

	/**
	 *	Tests that only HTTP and HTTPS are allowed, for requests and redirects.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstructRestrictsProtocols()
	{
		$curl	= new Net_CURL();
		self::assertSame( CURLPROTO_HTTP | CURLPROTO_HTTPS, $curl->getOption( CURLOPT_PROTOCOLS ) );
		self::assertSame( CURLPROTO_HTTP | CURLPROTO_HTTPS, $curl->getOption( CURLOPT_REDIR_PROTOCOLS ) );
	}

	/**
	 *	Tests that method 'exec' does not read local files or use other protocols.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecRejectsDisallowedProtocols()
	{
		$urls	= [
			'file://localhost'.__FILE__,
			'gopher://localhost/_test',
			'dict://localhost/test',
		];
		foreach( $urls as $url ){
			$curl	= new Net_CURL( $url );
			$result	= $curl->exec();
			self::assertSame( '', $result, $url );
			self::assertSame( CURLE_UNSUPPORTED_PROTOCOL, $curl->getInfo( Net_CURL::INFO_ERRNO ), $url );
		}

		$curl	= new Net_CURL( 'file://localhost'.__FILE__ );
		$this->expectException( 'RuntimeException' );
		$curl->exec( TRUE );
	}

	/**
	 *	Tests that method 'exec' does not follow redirects to other protocols.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecRejectsRedirectToFile()
	{
		if( NULL === self::$process )
			$this->markTestSkipped( 'Built-in web server could not be started' );

		$curl	= new Net_CURL( self::$url );
		self::assertSame( 'hello', $curl->exec( TRUE ) );

		$curl	= new Net_CURL( self::$url.'redirect-file' );
		$curl->setOption( CURLOPT_FOLLOWLOCATION, TRUE );
		$result	= $curl->exec();
		self::assertStringNotContainsString( 'Router script', $result );
		self::assertSame( CURLE_UNSUPPORTED_PROTOCOL, $curl->getInfo( Net_CURL::INFO_ERRNO ) );
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !extension_loaded( 'curl' ) )
			$this->markTestSkipped( 'Missing cURL support' );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'close'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClose()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::close();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExec()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::exec();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Exception of Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecException3()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::exec();
	}

	/**
	 *	Tests Method 'getHeader'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHeader()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getHeader();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetOption()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getOption();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getStatus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStatus()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::getStatus();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getStatus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetStatusException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'RuntimeException' );
		Net_CURL::getStatus();
	}

	/**
	 *	Tests Method 'hasError'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasError()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::hasError();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parseHeader'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseHeader()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::parseHeader();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOption()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::setOption();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException1()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::setOption();
	}

	/**
	 *	Tests Exception of Method 'setOption'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetOptionException2()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->expectException( 'InvalidArgumentException' );
		Net_CURL::setOption();
	}

	/**
	 *	Tests Method 'setTimeOut'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTimeOut()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Net_CURL::setTimeOut();
		self::assertEquals( $assertion, $creation );
	}
}

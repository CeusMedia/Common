<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net\HTTP\Reader, using PHP's built-in web server.
 *	@package		Tests.Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Reader;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;
use RuntimeException;

/**
 *	TestUnit of Net\HTTP\Reader, using PHP's built-in web server.
 *	@package		Tests.Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
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

		$command	= [PHP_BINARY, '-S', '127.0.0.1:'.$port, __DIR__.'/assets/readerServer.php'];
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

	public function setUp(): void
	{
		if( NULL === self::$process )
			$this->markTestSkipped( 'Built-in web server could not be started (or cURL is missing)' );
	}

	public function testGet(): void
	{
		$reader		= new Reader();
		$response	= $reader->get( self::$url, ['X-Request' => 'abc'] );
		self::assertSame( 'hello', $response->getBody() );
		self::assertSame( 'abc', $reader->getResponseHeader( 'X-Test' ) );
		self::assertSame( 'abc', $reader->getResponseHeader( 'x-test' ) );
		self::assertNull( $reader->getResponseHeader( 'X-Missing' ) );
	}

	public function testPost(): void
	{
		$reader		= new Reader();
		$response	= $reader->post( self::$url, ['key' => 'value'] );
		self::assertSame( 'posted:value', $response->getBody() );
		self::assertSame( 200, $reader->getCurlInfo( 'http_code' ) );
	}

	public function testGetCurlInfo(): void
	{
		$reader		= new Reader();
		$reader->get( self::$url );
		self::assertSame( 200, $reader->getCurlInfo( 'http_code' ) );
		self::assertStringStartsWith( 'text/plain', $reader->getCurlInfo( 'content_type' ) );
		self::assertSame( self::$url, $reader->getCurlInfo( 'url' ) );

		$info	= $reader->getCurlInfo();
		self::assertIsArray( $info );
		self::assertSame( 200, $info['http_code'] );
		self::assertSame( $info, $reader->getCurlInfo( '' ) );
	}

	public function testGetCurlInfoWithoutRequestException(): void
	{
		$this->expectException( RuntimeException::class );
		( new Reader() )->getCurlInfo( 'http_code' );
	}

	public function testGetCurlInfoInvalidKeyException(): void
	{
		$reader		= new Reader();
		$reader->get( self::$url );
		$this->expectException( InvalidArgumentException::class );
		$reader->getCurlInfo( 'not_existing' );
	}
}

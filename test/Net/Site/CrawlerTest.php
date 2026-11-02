<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net\Site\Crawler, MapBuilder and MapWriter, using PHP's built-in web server.
 *	@package		Tests.Net.Site
 */

namespace CeusMedia\CommonTest\Net\Site;

use CeusMedia\Common\Net\Site\Crawler;
use CeusMedia\Common\Net\Site\MapBuilder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\Site\Crawler, MapBuilder and MapWriter, using PHP's built-in web server.
 *	@package		Tests.Net.Site
 */
class CrawlerTest extends BaseCase
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
		$command	= [PHP_BINARY, '-S', '127.0.0.1:'.$port, __DIR__.'/assets/siteServer.php'];
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

	public function testResolveUrl(): void
	{
		$base	= 'http://host:8080/dir/page.html?q=1';
		self::assertSame( 'http://host:8080/dir/other.html', Crawler::resolveUrl( $base, 'other.html' ) );
		self::assertSame( 'http://host:8080/dir/other.html', Crawler::resolveUrl( $base, './other.html#frag' ) );
		self::assertSame( 'http://host:8080/root.html', Crawler::resolveUrl( $base, '/root.html' ) );
		self::assertSame( 'http://host:8080/up.html', Crawler::resolveUrl( $base, '../up.html' ) );
		self::assertSame( 'http://host:8080/up.html', Crawler::resolveUrl( $base, '../../../up.html' ) );
		self::assertSame( 'http://host:8080/dir/', Crawler::resolveUrl( $base, './' ) );
		self::assertSame( 'http://host:8080/dir/page.html?x=2', Crawler::resolveUrl( $base, '?x=2' ) );
		self::assertSame( 'https://other.org/a', Crawler::resolveUrl( $base, 'https://other.org/a' ) );
		self::assertSame( 'http://cdn.org/b', Crawler::resolveUrl( $base, '//cdn.org/b' ) );
	}

	public function testCrawl(): void
	{
		$this->skipWithoutServer();
		$crawler	= new Crawler( self::$url );
		$count		= $crawler->crawl( self::$url );
		$urls		= array_map( static fn( $link ) => substr( $link['url'], strlen( self::$url ) - 1 ), $crawler->getLinks() );
		sort( $urls );
		self::assertSame( ['/', '/a.html', '/a.html?x=1', '/sub/b.html', '/sub/c.html', '/sub/deep/d.html'], $urls );
		self::assertSame( 6, $count );

		$references	= [];
		foreach( $crawler->getLinks() as $link )
			$references[substr( $link['url'], strlen( self::$url ) - 1 )]	= $link['references'];
		self::assertSame( 2, $references['/a.html'] );
	}

	public function testCrawlWithDepth(): void
	{
		$this->skipWithoutServer();
		$crawler	= new Crawler( self::$url, 2 );
		$crawler->crawl( self::$url );
		$urls		= array_map( static fn( $link ) => substr( $link['url'], strlen( self::$url ) - 1 ), $crawler->getLinks() );
		sort( $urls );
		self::assertSame( ['/', '/a.html', '/sub/b.html'], $urls );
	}

	public function testMapBuilder(): void
	{
		$xml	= MapBuilder::build( ['http://example.org/?a=1&b=2'] );
		$document	= new \DOMDocument();
		self::assertTrue( $document->loadXML( $xml ) );
		self::assertSame( 'http://www.sitemaps.org/schemas/sitemap/0.9', $document->documentElement->namespaceURI );
		self::assertSame( 'http://example.org/?a=1&b=2', $document->getElementsByTagName( 'loc' )->item( 0 )->textContent );
	}

	protected function skipWithoutServer(): void
	{
		if( NULL === self::$process )
			$this->markTestSkipped( 'Built-in web server could not be started (or cURL is missing)' );
	}
}

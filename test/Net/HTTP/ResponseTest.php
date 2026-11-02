<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Response;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Response.
 *	@package		Tests.Net.HTTP
 */
class ResponseTest extends BaseCase
{
	public function testGetBodyLengthCountsBytes(): void
	{
		$response	= new Response();
		self::assertSame( 0, $response->getBodyLength() );
		$response->setBody( 'Größe €' );
		self::assertSame( strlen( 'Größe €' ), $response->getBodyLength() );
		self::assertSame( (string) strlen( 'Größe €' ), $response->getHeader( 'Content-Length', TRUE )->getValue() );

		$binary	= (string) gzencode( str_repeat( 'abc', 100 ) );
		$response->setBody( $binary );
		self::assertSame( strlen( $binary ), $response->getBodyLength() );
	}

	public function testToString(): void
	{
		$response	= new Response( 'HTTP', '1.1' );
		$response->setStatus( 404 );
		self::assertSame( "HTTP/1.1 404 Not Found\r\n\r\n", $response->toString() );

		$response->setBody( 'ä' );
		$expected	= "HTTP/1.1 404 Not Found\r\nContent-Length: 2\r\n\r\nä";
		self::assertSame( $expected, $response->toString() );
		self::assertSame( strlen( $expected ), $response->getLength() );

		$response->setBody( '' );
		self::assertSame( "HTTP/1.1 404 Not Found\r\nContent-Length: 0\r\n\r\n", $response->toString() );
	}

	public function testSetStatus(): void
	{
		$response	= new Response();
		$response->setStatus( '301' );
		self::assertSame( '301 Moved Permanently', $response->getStatus() );
		$response->setStatus( '299 Custom' );
		self::assertSame( '299 Custom', $response->getStatus() );
	}
}

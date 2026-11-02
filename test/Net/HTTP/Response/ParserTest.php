<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP\Response;

use CeusMedia\Common\Net\HTTP\Response\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Response\Parser.
 *	@package		Tests.Net.HTTP.Response
 */
class ParserTest extends BaseCase
{
	public function testFromString(): void
	{
		$response	= Parser::fromString( "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nX-Empty:\r\n\r\nline1\r\n\r\nline2" );
		self::assertSame( '1.1', $response->getVersion() );
		self::assertSame( '200 OK', $response->getStatus() );
		self::assertSame( 'text/plain', $response->getHeader( 'Content-Type', TRUE )->getValue() );
		self::assertSame( '', $response->getHeader( 'X-Empty', TRUE )->getValue() );
		self::assertSame( "line1\r\n\r\nline2", $response->getBody() );
	}

	public function testFromStringUsesLastHeaderBlock(): void
	{
		$string	= "HTTP/1.1 100 Continue\r\n\r\n"
			."HTTP/1.1 301 Moved Permanently\r\nLocation: /new\r\n\r\n"
			."HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n<p>final</p>";
		$response	= Parser::fromString( $string );
		self::assertSame( '200 OK', $response->getStatus() );
		self::assertFalse( $response->hasHeader( 'Location' ) );
		self::assertSame( '<p>final</p>', $response->getBody() );
	}

	public function testFromStringWithFalsyBody(): void
	{
		$response	= Parser::fromString( "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\n\r\n0" );
		self::assertSame( '0', $response->getBody() );
	}
}

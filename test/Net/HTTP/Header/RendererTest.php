<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP\Header;

use CeusMedia\Common\Net\HTTP\Header\Parser;
use CeusMedia\Common\Net\HTTP\Header\Renderer;
use CeusMedia\Common\Net\HTTP\Header\Section;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Header\Renderer and Parser.
 *	@package		Tests.Net.HTTP.Header
 */
class RendererTest extends BaseCase
{
	public function testRender(): void
	{
		self::assertSame( '', Renderer::render( new Section() ) );
		$section	= ( new Section() )->addFieldPair( 'Content-Type', 'text/plain' )->addFieldPair( 'X-Test', 'a' );
		self::assertSame( "Content-Type: text/plain\r\nX-Test: a\r\n", Renderer::render( $section ) );
	}

	public function testParse(): void
	{
		$section	= Parser::parse( "HTTP/1.1 200 OK\r\nContent-Type: text/plain\r\nX-Empty:\r\n" );
		self::assertSame( 'text/plain', $section->getField( 'content-type' )?->getValue() );
		self::assertSame( '', $section->getField( 'X-Empty' )?->getValue() );
	}
}

<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP\Sniffer;

use CeusMedia\Common\Net\HTTP\Sniffer\Encoding;
use CeusMedia\Common\Net\HTTP\Sniffer\MimeType;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Sniffer\MimeType and Encoding.
 *	@package		Tests.Net.HTTP.Sniffer
 */
class MimeTypeTest extends BaseCase
{
	protected array $server	= [];

	public function setUp(): void
	{
		$this->server	= $_SERVER;
	}

	public function tearDown(): void
	{
		$_SERVER	= $this->server;
	}

	public function testGetMimeType(): void
	{
		$allowed	= ['text/html', 'application/json', 'application/vnd.api+json', 'application/x-www-form-urlencoded'];
		$_SERVER['HTTP_ACCEPT']	= 'text/html;q=0.5, application/vnd.api+json';
		self::assertSame( 'application/vnd.api+json', MimeType::getMimeType( $allowed ) );

		$_SERVER['HTTP_ACCEPT']	= 'application/json; charset=utf-8; q=0.9, text/html; q=0.8';
		self::assertSame( 'application/json', MimeType::getMimeType( $allowed ) );

		$_SERVER['HTTP_ACCEPT']	= 'application/x-www-form-urlencoded';
		self::assertSame( 'application/x-www-form-urlencoded', MimeType::getMimeType( $allowed ) );

		$_SERVER['HTTP_ACCEPT']	= 'image/png, application/*;q=0.7';
		self::assertSame( 'application/json', MimeType::getMimeType( $allowed ) );

		$_SERVER['HTTP_ACCEPT']	= 'image/png';
		self::assertSame( 'text/html', MimeType::getMimeType( $allowed ) );

		unset( $_SERVER['HTTP_ACCEPT'] );
		self::assertSame( 'application/json', MimeType::getMimeType( $allowed, 'application/json' ) );
	}

	public function testGetEncoding(): void
	{
		$allowed	= ['gzip', 'deflate', 'x-gzip'];
		$_SERVER['HTTP_ACCEPT_ENCODING']	= 'deflate;q=0.5, GZIP';
		self::assertSame( 'gzip', Encoding::getEncoding( $allowed ) );

		$_SERVER['HTTP_ACCEPT_ENCODING']	= 'br, x-gzip;q=0.8';
		self::assertSame( 'x-gzip', Encoding::getEncoding( $allowed ) );

		$_SERVER['HTTP_ACCEPT_ENCODING']	= 'br';
		self::assertSame( 'gzip', Encoding::getEncoding( $allowed ) );
	}
}

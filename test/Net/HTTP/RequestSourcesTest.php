<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Request;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\Request sources.
 *	@package		Tests.Net.HTTP
 */
class RequestSourcesTest extends BaseCase
{
	protected array $backup	= [];

	public function setUp(): void
	{
		$this->backup	= [$_GET, $_POST, $_COOKIE, $_SESSION ?? NULL];
		$_GET		= ['a' => 'get'];
		$_POST		= ['b' => 'post'];
		$_COOKIE	= ['c' => 'cookie'];
		$_SESSION	= ['d' => 'session'];
	}

	public function tearDown(): void
	{
		[$_GET, $_POST, $_COOKIE, $_SESSION]	= $this->backup;
	}

	public function testFromEnvWithSessionAndCookie(): void
	{
		$request	= ( new Request() )->fromEnv( TRUE, TRUE );
		self::assertSame( ['d' => 'session'], $request->getAllFromSource( 'session' ) );
		self::assertSame( ['c' => 'cookie'], $request->getAllFromSource( 'COOKIE' ) );
		self::assertSame( 'session', $request->getFromSource( 'd', 'session' ) );
		self::assertTrue( $request->hasInSource( 'c', 'cookie' ) );
		self::assertSame( 'get', $request->get( 'a' ) );
		self::assertSame( 'session', $request->get( 'd' ) );
	}

	public function testFromEnvWithoutSessionAndCookie(): void
	{
		$request	= ( new Request() )->fromEnv();
		self::assertSame( [], $request->getAllFromSource( 'session' ) );
		self::assertNull( $request->get( 'c' ) );
	}
}

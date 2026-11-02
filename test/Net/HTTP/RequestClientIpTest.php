<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of client IP detection in Net_HTTP_Request.
 *	@package		Tests.Net.HTTP
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Request;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of client IP detection in Net_HTTP_Request.
 *	@package		Tests.Net.HTTP
 */
class RequestClientIpTest extends BaseCase
{
	public function testForwardedHeaderIgnoredWithoutTrustedProxy(): void
	{
		//  a client can send any X-Forwarded-For header itself
		self::assertSame( '203.0.113.7', Request::determineClientIp( '203.0.113.7', '127.0.0.1', [] ) );
	}

	public function testForwardedHeaderIgnoredFromUntrustedPeer(): void
	{
		self::assertSame( '203.0.113.7', Request::determineClientIp( '203.0.113.7', '198.51.100.1', ['10.0.0.1'] ) );
	}

	public function testClientBehindTrustedProxy(): void
	{
		self::assertSame( '198.51.100.1', Request::determineClientIp( '10.0.0.1', '198.51.100.1', ['10.0.0.1'] ) );
	}

	public function testSpoofedEntriesLeftOfClientAreIgnored(): void
	{
		//  client sent "X-Forwarded-For: 127.0.0.1", proxy appended the real client address
		self::assertSame( '198.51.100.1', Request::determineClientIp( '10.0.0.1', '127.0.0.1, 198.51.100.1', ['10.0.0.1'] ) );
	}

	public function testChainOfTrustedProxies(): void
	{
		$proxies	= ['10.0.0.0/8', '2001:db8::/32'];
		self::assertSame( '198.51.100.1', Request::determineClientIp( '10.0.0.2', '198.51.100.1, 10.1.2.3', $proxies ) );
		self::assertSame( '2001:db9::5', Request::determineClientIp( '2001:db8::1', '2001:db9::5', $proxies ) );
	}

	public function testInvalidHeaderFallsBackToPeer(): void
	{
		self::assertSame( '10.0.0.1', Request::determineClientIp( '10.0.0.1', 'unknown', ['10.0.0.1'] ) );
		self::assertSame( '10.0.0.1', Request::determineClientIp( '10.0.0.1', '', ['10.0.0.1'] ) );
	}

	public function testIsIpInList(): void
	{
		self::assertTrue( Request::isIpInList( '10.1.2.3', ['10.0.0.0/8'] ) );
		self::assertTrue( Request::isIpInList( '192.168.1.130', ['192.168.1.128/25'] ) );
		self::assertFalse( Request::isIpInList( '192.168.1.127', ['192.168.1.128/25'] ) );
		self::assertTrue( Request::isIpInList( '::1', ['::1'] ) );
		self::assertTrue( Request::isIpInList( 'fd12:3456::1', ['fd00::/8'] ) );
		self::assertFalse( Request::isIpInList( '10.0.0.1', ['fd00::/8'] ), 'IPv4 never matches IPv6 networks' );
		self::assertFalse( Request::isIpInList( 'not-an-ip', ['0.0.0.0/0'] ) );
		self::assertTrue( Request::isIpInList( '8.8.8.8', ['0.0.0.0/0'] ) );
	}

	public function testFromEnvUsesRemoteAddressAndTrustedProxies(): void
	{
		$backup	= [$_SERVER, getenv( 'REMOTE_ADDR' )];
		putenv( 'REMOTE_ADDR=10.0.0.1' );
		$_SERVER['HTTP_X_FORWARDED_FOR']	= '127.0.0.1, 198.51.100.1';
		try{
			$request	= ( new Request() )->fromEnv();
			self::assertSame( '10.0.0.1', $request->getIp(), 'no trusted proxy configured' );
			self::assertSame( '10.0.0.1', $request->getRemoteAddress() );
			self::assertSame( '198.51.100.1', $request->setTrustedProxies( ['10.0.0.1'] )->getIp() );

			Request::setDefaultTrustedProxies( ['10.0.0.0/24'] );
			self::assertSame( '198.51.100.1', ( new Request() )->fromEnv()->getIp() );
		}
		finally{
			Request::setDefaultTrustedProxies( [] );
			$_SERVER	= $backup[0];
			putenv( FALSE !== $backup[1] ? 'REMOTE_ADDR='.$backup[1] : 'REMOTE_ADDR' );
		}
	}
}

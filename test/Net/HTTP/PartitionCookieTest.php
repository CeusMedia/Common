<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net\HTTP\PartitionCookie.
 *	@package		Tests.Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\HTTP;

use CeusMedia\Common\Net\HTTP\PartitionCookie;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\HTTP\PartitionCookie.
 *	@package		Tests.Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class PartitionCookieTest extends BaseCase
{
	protected array $cookieBackup	= [];

	public function setUp(): void
	{
		$this->cookieBackup	= $_COOKIE;
		$_COOKIE	= [
			'other'		=> 'value',
			'part'		=> json_encode( ['a' => '1', 'b' => '2'] ),
		];
	}

	public function tearDown(): void
	{
		$_COOKIE	= $this->cookieBackup;
	}

	public function testConstructKeepsOtherCookies(): void
	{
		$expected	= $_COOKIE;
		new PartitionCookie( 'part' );
		self::assertSame( $expected, $_COOKIE );
	}

	public function testGet(): void
	{
		$cookie	= new PartitionCookie( 'part' );
		self::assertSame( '1', $cookie->get( 'a' ) );
		self::assertSame( ['a' => '1', 'b' => '2'], $cookie->getAll() );
		self::assertNull( $cookie->get( 'other' ) );
		self::assertSame( 'default', $cookie->get( 'c', 'default' ) );
	}

	public function testConstructWithMissingOrInvalidPartition(): void
	{
		$cookie	= new PartitionCookie( 'missing' );
		self::assertSame( [], $cookie->getAll() );

		$_COOKIE['broken']	= '{invalid';
		$cookie	= new PartitionCookie( 'broken' );
		self::assertSame( [], $cookie->getAll() );
	}

	public function testSetAndRemoveDoNotTouchOtherCookies(): void
	{
		$cookie	= new PartitionCookieWithoutHeaders( 'part' );
		self::assertTrue( $cookie->set( 'c', '3' ) );
		self::assertSame( '3', $cookie->get( 'c' ) );
		self::assertSame( 'value', $_COOKIE['other'] );
		self::assertArrayNotHasKey( 'c', $_COOKIE );
		self::assertSame( ['a' => '1', 'b' => '2', 'c' => '3'], json_decode( $_COOKIE['part'], TRUE ) );

		self::assertTrue( $cookie->remove( 'a' ) );
		self::assertNull( $cookie->get( 'a' ) );
		self::assertSame( 'value', $_COOKIE['other'] );
		self::assertSame( ['b' => '2', 'c' => '3'], json_decode( $_COOKIE['part'], TRUE ) );
	}
}

/**
 *	PartitionCookie which does not send headers, for testing in CLI.
 */
class PartitionCookieWithoutHeaders extends PartitionCookie
{
	protected function save( int $expires = 0, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		$_COOKIE[$this->partition]	= json_encode( $this->data );
		return TRUE;
	}
}

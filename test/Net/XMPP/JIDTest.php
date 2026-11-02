<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\XMPP;

use CeusMedia\Common\Net\XMPP\JID;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of Net\XMPP\JID.
 *	@package		Tests.Net.XMPP
 */
class JIDTest extends BaseCase
{
	public function testConstruct(): void
	{
		$jid	= new JID( 'user@example.org/phone' );
		self::assertSame( 'example.org', $jid->getDomain() );
		self::assertSame( 'user', $jid->getNode() );
		self::assertSame( 'phone', $jid->getResource() );
		self::assertSame( 'user@example.org/phone', $jid->get() );

		$jid	= new JID( 'example.org/bot' );
		self::assertSame( 'example.org', $jid->getDomain() );
		self::assertNull( $jid->getNode() );
		self::assertSame( 'bot', $jid->getResource() );

		$jid	= new JID( 'example.org', 'user' );
		self::assertSame( 'user@example.org', $jid->get() );
	}

	public function testDisassemble(): void
	{
		self::assertSame( ['domain' => 'example.org', 'node' => NULL, 'resource' => NULL], JID::disassemble( 'example.org' ) );
		self::assertSame( ['domain' => 'b.org', 'node' => 'a', 'resource' => 'r/s'], JID::disassemble( 'a@b.org/r/s' ) );
	}

	public function testIsValid(): void
	{
		self::assertTrue( JID::isValid( 'user@example.org' ) );
		self::assertTrue( JID::isValid( 'user@example.org/resource with space' ) );
		self::assertFalse( JID::isValid( 'a@b@c' ) );
		self::assertFalse( JID::isValid( 'user@exa mple.org' ) );
		self::assertFalse( JID::isValid( '' ) );
	}

	public function testInvalidException(): void
	{
		$this->expectException( InvalidArgumentException::class );
		new JID( 'a@b@c' );
	}
}

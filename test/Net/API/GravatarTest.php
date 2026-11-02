<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net\API;

use CeusMedia\Common\Net\API\Gravatar;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net\API\Gravatar.
 *	@package		Tests.Net.API
 */
class GravatarTest extends BaseCase
{
	public function testGetUrl(): void
	{
		$gravatar	= new Gravatar( 40, 'pg', 'identicon' );
		$hash		= md5( 'user@example.org' );
		self::assertSame( 'https://secure.gravatar.com/avatar/'.$hash.'?s=40&d=identicon&r=pg', $gravatar->getUrl( ' User@Example.org ' ) );
	}

	public function testRenderImage(): void
	{
		$html	= ( new Gravatar() )->renderImage( 'user@example.org', ['alt' => 'Avatar'] );
		self::assertStringContainsString( '?s=80&amp;d=mm&amp;r=g"', $html );
		self::assertStringNotContainsString( '&amp;amp;', $html );
		self::assertStringContainsString( 'width="80"', $html );
	}
}

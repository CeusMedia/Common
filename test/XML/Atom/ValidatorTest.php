<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\XML\Atom;

use CeusMedia\Common\XML\Atom\Parser;
use CeusMedia\Common\XML\Atom\Validator;
use CeusMedia\Common\XML\Element as XmlElement;
use CeusMedia\CommonTest\BaseCase;
use Exception;

/**
 *	TestUnit of XML_Atom_Validator.
 *	@package		Tests.xml.atom
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ValidatorTest extends BaseCase
{
	/**
	 *	Tests that a valid feed has no errors.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsValid(): void
	{
		$validator	= new Validator();
		self::assertTrue( $validator->isValid( $this->createFeed() ) );
		self::assertSame( [], $validator->getErrors() );
		self::assertSame( '', $validator->getFirstError() );
	}

	/**
	 *	Tests that a missing updated element of an entry is reported (failed with an undefined rule key before).
	 *	@access		public
	 *	@return		void
	 */
	public function testEntryWithoutUpdated(): void
	{
		$validator	= new Validator();
		$feed		= $this->createFeed( '<entry><id>urn:entry:1</id><title>One</title><link href="https://example.com/1"/></entry>' );
		self::assertFalse( $validator->isValid( $feed ) );
		self::assertSame( ['entry_updated'], array_keys( $validator->getErrors() ) );
		self::assertSame( 'Entry Element MUST contain exactly one updated element.', $validator->getFirstError() );
	}

	/**
	 *	Tests that a link without rel attribute counts as alternate link (RFC 4287, 4.2.7.2).
	 *	@access		public
	 *	@return		void
	 */
	public function testLinkWithoutRelIsAlternate(): void
	{
		$validator	= new Validator();
		$entry		= '<entry><id>urn:entry:1</id><title>One</title><updated>2026-11-12T10:00:00Z</updated>%s</entry>';

		self::assertTrue( $validator->isValid( $this->createFeed( sprintf( $entry, '<link href="https://example.com/1"/>' ) ) ) );

		self::assertFalse( $validator->isValid( $this->createFeed( sprintf( $entry, '<link rel="related" href="https://example.com/1"/>' ) ) ) );
		self::assertSame( ['entry_link_alt'], array_keys( $validator->getErrors() ) );

		$links	= '<link href="https://example.com/1"/><link rel="alternate" href="https://example.com/2"/>';
		self::assertFalse( $validator->isValid( $this->createFeed( sprintf( $entry, $links ) ) ) );
		self::assertSame( ['entry_link_unique'], array_keys( $validator->getErrors() ) );
	}

	/**
	 *	Tests that the parser reports a broken rule as exception instead of failing.
	 *	@access		public
	 *	@return		void
	 */
	public function testParserThrowsFirstError(): void
	{
		$xml	= $this->createFeed( '<entry><id>urn:entry:1</id><title>One</title><link href="https://example.com/1"/></entry>' )->asXML();
		$this->expectException( Exception::class );
		$this->expectExceptionMessage( 'Entry Element MUST contain exactly one updated element.' );
		( new Parser() )->parse( (string) $xml );
	}

	protected function createFeed( ?string $entry = NULL ): XmlElement
	{
		$entry	??= '<entry><id>urn:entry:1</id><title>One</title><updated>2026-11-12T10:00:00Z</updated><link href="https://example.com/1"/></entry>';
		return new XmlElement( '<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
	<id>urn:feed:test</id>
	<title>Test</title>
	<updated>2026-11-12T10:00:00Z</updated>
	<author><name>Test</name></author>
	<link href="https://example.com/"/>
	'.$entry.'
</feed>' );
	}
}

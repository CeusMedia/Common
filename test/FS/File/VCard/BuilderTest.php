<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\VCard;

use CeusMedia\Common\ADT\VCard;
use CeusMedia\Common\FS\File\VCard\Builder;
use CeusMedia\Common\FS\File\VCard\Parser;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\File\VCard\Builder and Parser.
 *	@package		Tests.FS.File.VCard
 */
class BuilderTest extends BaseCase
{
	public function testRoundTrip(): void
	{
		$card	= new VCard();
		$card->setName( 'Doe', 'John', NULL, 'Dr.', NULL );
		$card->setFormattedName( 'Dr. John Doe' );
		$card->addAddress( 'Main St. 1; Rear', '', 'Berlin', 'BE', '10115', 'Germany', NULL, ['work'] );
		$card->addNickname( 'Johnny' );
		$card->addNickname( 'J, D' );
		$card->setOrganisation( 'ACME; Inc.', 'R&D' );
		$card->setTitle( "Line1\nLine2" );
		$card->addEmail( 'john@example.org', ['internet', 'pref'] );
		$card->addUrl( 'https://example.org/?a=1;b=2', ['work'] );

		$string	= Builder::build( $card );
		self::assertStringContainsString( 'N:Doe;John;;Dr.;', $string );
		self::assertStringContainsString( 'ADR;TYPE=work:;;Main St. 1\; Rear;Berlin;BE;10115;Germany', $string );

		$parsed	= Parser::parse( $string );
		self::assertEquals( $card->getNameFields(), $parsed->getNameFields() );
		self::assertEquals( $card->getAddresses(), $parsed->getAddresses() );
		self::assertEquals( $card->getNicknames(), $parsed->getNicknames() );
		self::assertEquals( $card->getOrganisationFields(), $parsed->getOrganisationFields() );
		self::assertSame( $card->getTitle(), $parsed->getTitle() );
		self::assertEquals( $card->getEmails(), $parsed->getEmails() );
		self::assertEquals( $card->getUrls(), $parsed->getUrls() );
	}

	public function testParseFoldedCrlfAndBareTypes(): void
	{
		$string	= "BEGIN:VCARD\r\nVERSION:2.1\r\nN:Doe\r\nFN:John\r\n  Doe\r\nTEL;WORK;VOICE:+49 30 1234\r\nEND:VCARD\r\n";
		$card	= Parser::parse( $string );
		self::assertSame( 'John Doe', $card->getFormattedName() );
		self::assertSame( 'Doe', $card->getNameFields()['familyName'] );
		self::assertSame( ['+49 30 1234' => ['WORK', 'VOICE']], $card->getPhones() );
	}
}

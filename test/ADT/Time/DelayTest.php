<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\ADT\Time;

use CeusMedia\Common\ADT\Time\Delay;
use CeusMedia\Common\ADT\URN;
use CeusMedia\Common\ADT\VCard;
use CeusMedia\CommonTest\BaseCase;
use InvalidArgumentException;

/**
 *	TestUnit of ADT\Time\Delay, URN and VCard field getters.
 *	@package		Tests.ADT.Time
 */
class DelayTest extends BaseCase
{
	public function testDelay(): void
	{
		$delay	= new Delay( 30 );
		self::assertTrue( $delay->isActive() );
		self::assertSame( 1, $delay->getNumberRuns() );
		usleep( 40000 );
		self::assertTrue( $delay->isReached() );
		$delay->restart();
		self::assertSame( 2, $delay->getNumberRuns() );
	}

	public function testUrnWithoutSpecificString(): void
	{
		self::assertSame( 'urn:isbn:0451450523', (string) new URN( 'urn:isbn:0451450523' ) );
		$this->expectException( InvalidArgumentException::class );
		new URN( 'isbn' );
	}

	public function testVCardEmptyFields(): void
	{
		$card	= new VCard();
		self::assertNull( $card->getNameField( 'additionalNames' ) );
		self::assertNull( $card->getOrganisationField( 'unit' ) );
	}
}

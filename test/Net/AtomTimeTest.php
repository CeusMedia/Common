<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\Net;

use CeusMedia\Common\Net\AtomTime;
use CeusMedia\CommonTest\BaseCase;
use DateTime;
use DateTimeZone;

class AtomTimeTest extends BaseCase
{
	public function testGet()
	{
		$dateTimeZone	= new DateTimeZone( ini_get('date.timezone') );
		$actual			= AtomTime::get( $dateTimeZone );
		self::assertIsObject( $actual );

		$now	= new DateTime();
		$diff	= $now->diff( $actual );
		self::assertEquals( 0, $diff->format('%a') );
	}

	public function testGetTimestamp()
	{
		$dateTimeZone	= new DateTimeZone( ini_get('date.timezone') );
		$actual			= AtomTime::getTimestamp( $dateTimeZone );
		self::assertIsInt( $actual );
		self::assertLessThan( 60, abs( time() - $actual ) );
	}
}
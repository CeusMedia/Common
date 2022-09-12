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
		$this->assertIsObject( $actual );

		$now	= new DateTime();
		$diff	= $now->diff( $actual );
		$this->assertEquals( 0, $diff->format('%a') );
	}

	public function testGetTimestamp()
	{
		$dateTimeZone	= new DateTimeZone( ini_get('date.timezone') );
		$actual			= AtomTime::getTimestamp( $dateTimeZone );
		$this->assertIsInt( $actual );
		$this->assertLessThan( 60, abs( time() - $actual ) );
	}
}
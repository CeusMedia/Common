<?php

namespace CeusMedia\Common\Test\Net;

use CeusMedia\Common\Net\AtomTime;
use CeusMedia\Common\Test\BaseCase;
use DateTime;
use DateTimeZone;

class AtomTimeTest extends BaseCase
{
	public function testGet()
	{
		$dateTimeZone	= new DateTimeZone( ini_get('date.timezone') );
		$actual	= AtomTime::get( $dateTimeZone );
		$this->assertIsObject( $actual );

		$now	= new DateTime();
		$diff	= $now->diff( $actual );
		$this->assertEquals( 0, $diff->format('%a') );
	}

	public function testGetTimestamp()
	{
		$dateTimeZone	= new DateTimeZone( ini_get('date.timezone') );
		$actual	= AtomTime::getTimestamp( $dateTimeZone );
		$this->assertIsInt( $actual );

		$this->assertLessThan( 60, abs( time() - $actual ) );
	}
}
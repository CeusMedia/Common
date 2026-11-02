<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\Gantt;

use CeusMedia\Common\FS\File\Gantt\MeetingReader;
use CeusMedia\CommonTest\BaseCase;
use ReflectionMethod;

/**
 *	TestUnit of FS\File\Gantt\MeetingReader.
 *	@package		Tests.FS.File.Gantt
 */
class MeetingReaderTest extends BaseCase
{
	public function testCalculateEndDateOverDaylightSavingTime(): void
	{
		$timezone	= date_default_timezone_get();
		date_default_timezone_set( 'Europe/Berlin' );
		$method	= new ReflectionMethod( MeetingReader::class, 'calculateEndDate' );
		self::assertSame( '2023-10-30', $method->invoke( NULL, '2023-10-29', 1 ) );
		self::assertSame( '2024-04-01', $method->invoke( NULL, '2024-03-31', 1 ) );
		self::assertSame( '2024-01-11', $method->invoke( NULL, '2024-01-01', 10 ) );
		date_default_timezone_set( $timezone );
	}
}

<?php
declare( strict_types = 1 );
/**
 *	TestUnit of FS_File_ICal_Parser.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\ICal;

use CeusMedia\Common\FS\File\ICal\Builder;
use CeusMedia\Common\FS\File\ICal\Parser;
use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS_File_ICal_Parser.
 *	@package		Tests.FS.File.ICal
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ParserTest extends BaseCase
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Parser::__construct();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'parse'.
	 *	@access		public
	 *	@return		void
	 */
	public function testParse()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Parser::parse();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests parsing parameters separated by semicolons, with quoted values.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseLineParameters(): void
	{
		$method	= new \ReflectionMethod( Parser::class, 'parseLine' );
		$parsed	= $method->invoke( NULL, 'DTSTART;TZID=Europe/Berlin;VALUE=DATE-TIME:20261102T100000' );
		self::assertSame( 'DTSTART', $parsed['name'] );
		self::assertSame( ['TZID' => 'Europe/Berlin', 'VALUE' => 'DATE-TIME'], $parsed['param'] );
		self::assertSame( '20261102T100000', $parsed['value'] );

		$parsed	= $method->invoke( NULL, 'ATTENDEE;CN="Doe, John";DELEGATED-TO="mailto:a@b.c","mailto:d@e.f":mailto:x@y.z' );
		self::assertSame( 'ATTENDEE', $parsed['name'] );
		self::assertSame( ['CN' => 'Doe, John', 'DELEGATED-TO' => '"mailto:a@b.c","mailto:d@e.f"'], $parsed['param'] );
		self::assertSame( 'mailto:x@y.z', $parsed['value'] );
	}

	/**
	 *	Tests parsing parameters separated by commas, as written by older versions of the builder.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseLineLegacyCommaSeparator(): void
	{
		$method	= new \ReflectionMethod( Parser::class, 'parseLine' );
		$parsed	= $method->invoke( NULL, 'DTSTART;TZID=Europe/Berlin,VALUE=DATE:20261102' );
		self::assertSame( ['TZID' => 'Europe/Berlin', 'VALUE' => 'DATE'], $parsed['param'] );
		self::assertSame( '20261102', $parsed['value'] );
	}

	/**
	 *	Tests that building and parsing keeps names, parameters and UTF-8 values.
	 *	@access		public
	 *	@return		void
	 */
	public function testParseBuiltCalendar(): void
	{
		$summary	= str_repeat( 'Größenänderung € ', 8 );
		$event		= new Node( 'vevent' );
		$start		= new Node( 'dtstart', '20261102T100000' );
		$start->setAttribute( 'tzid', 'Europe/Berlin' );
		$start->setAttribute( 'value', 'DATE-TIME' );
		$event->addChild( $start );
		$attendee	= new Node( 'attendee', 'mailto:x@y.z' );
		$attendee->setAttribute( 'cn', 'Doe, John' );
		$event->addChild( $attendee );
		$event->addChild( new Node( 'summary', $summary ) );
		$calendar	= new Node( 'vcalendar' );
		$calendar->addChild( $event );
		$tree		= new Node( 'root' );
		$tree->addChild( $calendar );

		$ical		= ( new Builder( "\r\n" ) )->build( $tree );
		self::assertStringContainsString( 'DTSTART;TZID=Europe/Berlin,VALUE=DATE-TIME:20261102T100000', $ical );
		self::assertStringContainsString( 'ATTENDEE;CN="Doe, John":mailto:x@y.z', $ical );

		$parsed		= ( new Parser( "\r\n" ) )->parse( 'root', $ical );
		$event		= $parsed->getChildren()[0]->getChildren()[0];
		self::assertSame( 'vevent', $event->getNodeName() );
		$children	= $event->getChildren();
		self::assertSame( 'Europe/Berlin', $children[0]->getAttribute( 'tzid' ) );
		self::assertSame( 'DATE-TIME', $children[0]->getAttribute( 'value' ) );
		self::assertSame( '20261102T100000', $children[0]->getContent() );
		self::assertSame( 'Doe, John', $children[1]->getAttribute( 'cn' ) );
		self::assertSame( 'mailto:x@y.z', $children[1]->getContent() );
		self::assertSame( $summary, $children[2]->getContent() );
	}
}

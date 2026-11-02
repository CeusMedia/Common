<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File\Log\Tracker;

use CeusMedia\Common\FS\File\Log\Tracker\Reader;
use CeusMedia\Common\FS\File\Log\Tracker\ShortReader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\File\Log\Tracker\Reader and ShortReader.
 *	@package		Tests.FS.File.Log.Tracker
 */
class ReaderTest extends BaseCase
{
	protected string $fileName;

	public function setUp(): void
	{
		$this->fileName	= sys_get_temp_dir().'/CeusMediaCommonTestTracker'.getmypid().'.log';
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testParseAndTable(): void
	{
		$t	= 1700000000;
		$lines	= [
			$t.' [14.11.2023 22:13:20] 10.0.0.1 /index ref "<script>alert(1)</script>"',
			'not a log line',
			($t + 60).' [14.11.2023 22:14:20] 10.0.0.1 /page ref "Agent"',
			($t + 7200).' [15.11.2023 00:13:20] 10.0.0.1 /later ref "Agent"',
		];
		file_put_contents( $this->fileName, implode( "\n", $lines )."\n" );
		$reader	= new Reader( $this->fileName, '127.0.0.1', TRUE );
		self::assertSame( 3, $reader->getVisits() );
		self::assertSame( 2, $reader->getVisitors() );
		self::assertSame( 1.5, $reader->getPagesPerVisitor() );
		$table	= $reader->getTable();
		self::assertStringNotContainsString( '<script>', $table );
		self::assertStringContainsString( '&lt;script&gt;', $table );
	}

	public function testEmptyLog(): void
	{
		file_put_contents( $this->fileName, '' );
		self::assertSame( 0.0, ( new Reader( $this->fileName, '', TRUE ) )->getPagesPerVisitor() );
	}

	public function testShortReaderWithAdditionalFields(): void
	{
		file_put_contents( $this->fileName, "1700000000|10.0.0.1|/index|http://ref.org|Agent|extra|data\n1700000100|10.0.0.2|/x\n" );
		$reader	= new ShortReader( $this->fileName );
		$reader->parse();
		$data	= $reader->getData();
		self::assertSame( 'Agent', $data[0]['http_user_agent'] );
		self::assertSame( '', $data[1]['http_referer'] );
		self::assertSame( 2, $reader->getVisitors() );
	}
}

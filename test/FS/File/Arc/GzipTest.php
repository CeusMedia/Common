<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Gzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Arc;

use CeusMedia\Common\FS\File\Arc\Gzip;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Gzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class GzipTest extends BaseCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private string $fileName;

	protected string $path;

	public function testWriteString(): void
	{
		$arc	= new Gzip( $this->fileName );
		$arc->writeString( "test" );

		$creation	= file_exists( $this->fileName );
		self::assertTrue( $creation );

		//  gzip format (RFC 1952), not zlib format
		$content	= file_get_contents( $this->fileName );
		self::assertStringStartsWith( "\x1f\x8b", $content );
		self::assertEquals( "test", gzdecode( $content ) );
	}

	public function testReadStringFromGzipFile(): void
	{
		file_put_contents( $this->fileName, gzencode( "content of a real gzip file", 9 ) );
		$arc	= new Gzip( $this->fileName );
		self::assertEquals( "content of a real gzip file", $arc->readString() );
	}

	/**
	 *	Files written by versions before 1.1.1 are in zlib format.
	 */
	public function testReadStringFromLegacyZlibFile(): void
	{
		file_put_contents( $this->fileName, gzcompress( "written by version 1.1.0" ) );
		$arc	= new Gzip( $this->fileName );
		self::assertEquals( "written by version 1.1.0", $arc->readString() );
	}

	public function testReadStringException(): void
	{
		file_put_contents( $this->fileName, "not compressed" );
		$arc	= new Gzip( $this->fileName );
		$this->expectException( \RuntimeException::class );
		$arc->readString();
	}

	public function testReadString(): void
	{
		$arc	= new Gzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		self::assertEquals( $assertion, $creation );
	}

	protected function setUp(): void
	{
		if( !extension_loaded( 'zlib' ) )
			$this->markTestSkipped( 'Support for zlib is missing' );
		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.gz";
	}

	protected function tearDown(): void
	{
		@unlink( $this->fileName );
	}
}

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
		$this->assertTrue( $creation );

		$assertion	= "test";
		$creation	= gzuncompress( file_get_contents( $this->fileName ) );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString(): void
	{
		$arc	= new Gzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}

	protected function setUp(): void
	{
		if( !extension_loaded( 'zlib' ) )
			$this->markTestSkipped( 'Support for bzip2 is missing' );
		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.gz";
	}

	protected function tearDown(): void
	{
		@unlink( $this->fileName );
	}
}

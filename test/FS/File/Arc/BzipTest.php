<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Bzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Arc;

use CeusMedia\Common\FS\File\Arc\Bzip;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Bzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BzipTest extends BaseCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private string $fileName;

	protected string $path;

	public function testWriteString(): void
	{
		$arc	= new Bzip( $this->fileName );
		$arc->writeString( "test" );

		$this->assertFileExists( $this->fileName );

		$assertion	= bzcompress( "test" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString(): void
	{
		$arc	= new Bzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}

	protected function setUp(): void
	{
		if( !extension_loaded( 'bz2' ) )
			$this->markTestSkipped( 'Support for bzip2 is missing' );

		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.bz";
	}

	protected function tearDown(): void
	{
		@unlink( $this->fileName );
	}
}

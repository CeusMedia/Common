<?php
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
	private $fileName;

	public function setUp(): void
	{
		if( !extension_loaded( 'zlib' ) )
			$this->markTestSkipped( 'Support for bzip2 is missing' );
		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.gz";
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testWriteString()
	{
		$arc	= new Gzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test";
		$creation	= gzuncompress( file_get_contents( $this->fileName ) );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString()
	{
		$arc	= new Gzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}

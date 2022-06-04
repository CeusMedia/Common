<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\FS\File\Arc;

use CeusMedia\Common\FS\File\Arc\Bzip;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Bzip File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BzipTest extends BaseCase
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;

	public function setUp(): void
	{
		if( !extension_loaded( 'bz2' ) )
			$this->markTestSkipped( 'Support for bzip2 is missing' );

		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.bz";
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testWriteString()
	{
		$arc	= new Bzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );

		$assertion	= bzcompress( "test" );
		$creation	= file_get_contents( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}

	public function testReadString()
	{
		$arc	= new Bzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}

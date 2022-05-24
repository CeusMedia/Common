<?php
/**
 *	TestUnit of Gzip File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Gzip File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_FS_File_Arc_GzipTest extends Test_Case
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
		$arc	= new FS_File_Arc_Gzip( $this->fileName );
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
		$arc	= new FS_File_Arc_Gzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}

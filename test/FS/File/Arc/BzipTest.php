<?php
/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Bzip File.
 *	@package		Tests.file.arc
 *	@extends		Test_Case
 *	@uses			FS_File_Arc_Bzip
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_FS_File_Arc_BzipTest extends Test_Case
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
		$arc	= new FS_File_Arc_Bzip( $this->fileName );
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
		$arc	= new FS_File_Arc_Bzip( $this->fileName );
		$arc->writeString( "test" );

		$assertion	= "test";
		$creation	= $arc->readString();
		$this->assertEquals( $assertion, $creation );
	}
}

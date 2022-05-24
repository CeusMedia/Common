<?php
/**
 *	TestUnit of T File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Tar File.
 *	@package		Tests.file.arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class Test_FS_File_Arc_TarTest extends Test_Case
{
	/**	@var	string		$fileName		URL of Archive File Name */
	private $fileName;

	public function setUp(): void
	{
		$this->path	= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.tar";
	}

	public function tearDown(): void
	{
		@unlink( $this->fileName );
	}

	public function testAddFile()
	{
		$arc	= new FS_File_Arc_Tar();
		$arc->addFile( $this->path."TarTest.php" );

		$this->assertTrue( $arc->save( $this->fileName ) > 0 );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}

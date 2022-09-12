<?php
declare( strict_types = 1 );
/**
 *	TestUnit of T File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File\Arc;

use CeusMedia\Common\FS\File\Arc\Tar;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Tar File.
 *	@package		Tests.FS.File.Arc
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class TarTest extends BaseCase
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
		$arc	= new Tar();
		$arc->addFile( $this->path."TarTest.php" );

		$this->assertTrue( $arc->save( $this->fileName ) > 0 );

		$assertion	= TRUE;
		$creation	= file_exists( $this->fileName );
		$this->assertEquals( $assertion, $creation );
	}
}

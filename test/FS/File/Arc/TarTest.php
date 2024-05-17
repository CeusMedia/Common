<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Tar File.
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
	private string $fileName;

	protected string $path;

	public function testAddFile(): void
	{
		$arc	= new Tar();
		$arc->addFile( $this->path."TarTest.php" );

		self::assertTrue( $arc->save( $this->fileName ) > 0 );

		$creation	= file_exists( $this->fileName );
		self::assertTrue( $creation );
	}

	protected function setUp(): void
	{
		$this->path		= dirname( __FILE__ )."/";
		$this->fileName	= $this->path."test.tar";
	}

	protected function tearDown(): void
	{
		@unlink( $this->fileName );
	}
}

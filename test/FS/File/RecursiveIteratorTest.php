<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\RecursiveIterator;
use CeusMedia\CommonTest\BaseCase;
use CeusMedia\CommonTest\FS\TempFolderTrait;

/**
 *	TestUnit of FS\File\RecursiveIterator.
 *	@package		Tests.FS.File
 */
class RecursiveIteratorTest extends BaseCase
{
	use TempFolderTrait;

	public function testIterate(): void
	{
		$path	= $this->createTempFolder();
		mkdir( $path.'sub/deep', 0777, TRUE );
		touch( $path.'a.txt' );
		touch( $path.'sub/b.txt' );
		touch( $path.'sub/deep/c.txt' );
		$files	= [];
		foreach( new RecursiveIterator( $path ) as $entry )
			$files[]	= substr( $entry->getPathname(), strlen( $path ) );
		sort( $files );
		self::assertSame( ['a.txt', 'sub/b.txt', 'sub/deep/c.txt'], $files );
		$this->removeTempFolder();
	}
}

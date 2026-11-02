<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

namespace CeusMedia\CommonTest\FS;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\FS;
use CeusMedia\Common\FS\Folder;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of FS\Folder.
 *	@package		Tests.FS
 */
class FolderTest extends BaseCase
{
	use TempFolderTrait;

	public function setUp(): void
	{
		$this->createTempFolder();
	}

	public function tearDown(): void
	{
		$this->removeTempFolder();
	}

	public function testCreateAndIndex(): void
	{
		$folder	= new Folder( $this->tempPath.'root', TRUE );
		self::assertTrue( $folder->exists() );
		$folder->createFolder( 'sub' )->createFile( 'nested.txt', 'x' );
		$file	= $folder->createFile( 'zero.txt', '0' );
		self::assertSame( '0', $file->getContent() );

		self::assertSame( ['sub', 'zero.txt'], $folder->index()->getKeys() );
		self::assertSame( ['zero.txt'], $folder->index( FS::TYPE_FILE )->getKeys() );
		self::assertSame( ['sub'], $folder->index( FS::TYPE_FOLDER )->getKeys() );
		self::assertSame( 2, $folder->count() );
		self::assertSame( 2, $folder->count( FS::TYPE_FILE, TRUE ) );
		self::assertTrue( $folder->has( 'sub', FS::TYPE_FOLDER ) );
		self::assertFalse( $folder->has( 'sub', FS::TYPE_FILE ) );
	}

	public function testCreateFailsException(): void
	{
		file_put_contents( $this->tempPath.'file', 'x' );
		$folder	= new Folder( $this->tempPath.'file/sub' );
		self::assertFalse( $folder->create( 0777, FALSE ) );
		$this->expectException( IoException::class );
		$folder->create();
	}

	public function testRename(): void
	{
		$folder	= new Folder( $this->tempPath.'a', TRUE );
		self::assertTrue( $folder->rename( $this->tempPath.'b' ) );
		self::assertSame( $this->tempPath.'b', $folder->getPathName() );
		self::assertDirectoryExists( $this->tempPath.'b' );
	}
}

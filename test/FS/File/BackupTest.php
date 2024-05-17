<?php
/** @noinspection PhpMultipleClassDeclarationsInspection */

declare( strict_types = 1 );

/**
 *	Test class for FS_File_Backup.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\FS\File;

use CeusMedia\Common\FS\File\Backup;
use CeusMedia\CommonTest\BaseCase;

/**
 *	Test class for FS_File_Backup.
 *	@package		Tests.FS.File
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class BackupTest extends BaseCase
{
	protected $filePath;
	protected $path;
	protected $time;
	protected $file;

	public function setUp(): void
	{
		$this->time	= time();
		$this->path	= __DIR__.'/';
		$this->filePath	= $this->path."test.txt";

		if( !file_exists( $this->path ) )
			mkdir( $this->path );
		file_put_contents( $this->filePath, $this->time );
		$this->file	= new Backup( $this->filePath );
	}

	public function tearDown(): void
	{
		@unlink( $this->filePath );
		@unlink( $this->filePath.'~' );
		@unlink( $this->filePath.'.~1~' );
		@unlink( $this->filePath.'.~2~' );
		@unlink( $this->filePath.'.~3~' );
		@rmdir( $this->path );
	}

	public function testGetContent1(){
		$this->file->store();
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		self::assertEquals( "change-0", $this->file->getContent( 1 ) );
		self::assertEquals( "change-0", $this->file->getContent( -1 ) );
		self::assertEquals( $this->time, $this->file->getContent( 0 ) );
	}

	/**
	 */
	public function test_getContent_fromString_expectTypeError()
	{
		$this->expectException( 'TypeError' );
		$this->file->store();
		/** @noinspection PhpStrictTypeCheckingInspection */
		$this->file->getContent( "a" );
	}

	/**
	 */
	public function testGetContentException2(){
		$this->expectException( 'OutOfBoundsException' );
		$this->file->store();
		$this->file->getContent( -2 );
	}

	/**
	 */
	public function testGetContentException3(){
		$this->expectException( 'OutOfRangeException' );
		$this->file->store();
		$this->file->getContent( 1 );
	}

	public function testGetVersions1(){
		self::assertEquals( [], $this->file->getVersions() );
	}

	public function testGetVersions2(){
		$this->file->store();
		self::assertEquals( array( 0 ), array_keys( $this->file->getVersions() ) );
	}

	public function testGetVersions3(){
		$this->file->store();
		$this->file->store();
		self::assertEquals( array( 0, 1 ), array_keys( $this->file->getVersions() ) );
	}

	public function testMove(){
		$this->file->store();
		$this->file->store();

		self::assertTrue( file_exists( $this->filePath ) );
		self::assertTrue( file_exists( $this->filePath.'~' ) );
		self::assertTrue( file_exists( $this->filePath.'.~1~' ) );

		$target	= $this->path.'text.txt';
		$this->file->move( $target );
		self::assertTrue( file_exists( $target ) );
		self::assertTrue( file_exists( $target.'~' ) );
		self::assertTrue( file_exists( $target.'.~1~' ) );

		self::assertFalse( file_exists( $this->filePath ) );
		self::assertFalse( file_exists( $this->filePath.'~' ) );
		self::assertFalse( file_exists( $this->filePath.'.~1~' ) );

		$this->file->move( $this->filePath );
	}

	/**
	 */
	public function testMoveException(){
		$this->expectException( 'RuntimeException' );
		$this->file->move( $this->path.'notexistingFolder/filename.test' );
	}

	public function testRemove1(){
		$this->file->store();
		self::assertTrue( file_exists( $this->filePath.'~' ) );
		$this->file->remove();
		self::assertTrue( !file_exists( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->remove( -1 );
		self::assertTrue( !file_exists( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->store();
		$this->file->remove( -1 );
		$this->file->remove( -1 );
		self::assertTrue( !file_exists( $this->filePath.'~' ) );
	}

	public function testRemove2(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();

		$this->file->remove( 1 );
		self::assertEquals( "change-2", file_get_contents( $this->filePath.'.~1~' ) );
	}

	public function testRemove3(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-3" );

		$this->file->remove( 0 );
		self::assertEquals( "change-1", file_get_contents( $this->filePath.'~' ) );

		$this->file->remove( 0 );
		self::assertEquals( "change-2", file_get_contents( $this->filePath.'~' ) );
	}

	/**
	 */
	public function testRemoveException1(){
		$this->expectException( 'InvalidArgumentException' );
		$this->file->remove( "a" );
	}

	/**
	 */
	public function testRemoveException2(){
		$this->expectException( 'OutOfBoundsException' );
		$this->file->remove( -2 );
	}

	/**
	 */
	public function testRemoveException3(){
		$this->expectException( 'OutOfRangeException' );
		$this->file->remove( 4 );
	}

	/**
	 */
	public function testRemoveException4(){
		$this->expectException( 'OutOfRangeException' );
		$this->file->remove();
	}

	public function testRestore1(){
		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		$this->file->restore();
		self::assertEquals( $this->time, file_get_contents( $this->filePath ) );
		self::assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		$this->file->restore( -1 );
		self::assertEquals( $this->time, file_get_contents( $this->filePath ) );
		self::assertTrue( file_exists( $this->filePath.'~' ) );
	}

	public function testRestore2(){
		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		self::assertEquals( "changed", file_get_contents( $this->filePath ) );

		$this->file->restore( -1, TRUE );
		self::assertEquals( $this->time, file_get_contents( $this->filePath ) );
		self::assertTrue( !file_exists( $this->filePath.'~' ) );
	}

	public function testRestore3(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-3" );
		self::assertEquals( $this->file->getVersion(), 2 );

		$this->file->restore( 1 );
		self::assertEquals( "change-1", file_get_contents( $this->filePath ) );
	}

	public function testRestoreWithRemoveForwards(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-3" );
		self::assertEquals( $this->file->getVersion(), 2 );

		$this->file->restore( 0, TRUE );
		self::assertEquals( "change-0", file_get_contents( $this->filePath ) );
		self::assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->restore( 0, TRUE );
		self::assertEquals( "change-1", file_get_contents( $this->filePath ) );
		self::assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->restore( 0, TRUE );
		self::assertEquals( "change-2", file_get_contents( $this->filePath ) );
		self::assertFalse( file_exists( $this->filePath.'~' ) );
	}

	public function testRestoreWithPreservedTimestamp(){
		$timestamp	= filemtime( $this->filePath ) - 5;
		touch( $this->filePath, $timestamp );
		clearstatcache();
		$this->file->store();
		self::assertEquals( $timestamp, filemtime( $this->filePath.'~' ) );

		$this->file->restore();
		self::assertEquals( filemtime( $this->filePath ), $timestamp );
	}

	/**
	 */
	public function testRestoreException1(){
		$this->expectException( 'InvalidArgumentException' );
		$this->file->restore( "a" );
	}

	/**
	 */
	public function testRestoreException2(){
		$this->expectException( 'OutOfBoundsException' );
		$this->file->restore( -2 );
	}

	/**
	 */
	public function testRestoreException3(){
		$this->expectException( 'RuntimeException' );
		$this->file->store();
		$this->tearDown();
		$this->file->restore();
	}

	public function testSetContent(){
		$this->file->store();
		$this->file->setContent( 0, 'new content' );
		self::assertEquals( 'new content', file_get_contents( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->setContent( 1, 'even newer content' );
		self::assertEquals( 'even newer content', file_get_contents( $this->filePath.'.~1~' ) );
	}

	/**
	 */
	public function testSetContentException1(){
		$this->expectException( 'InvalidArgumentException' );
		$this->file->store();
		$this->file->setContent( 'wrong', 'new content' );
	}

	/**
	 */
	public function testSetContentException2(){
		$this->expectException( 'OutOfBoundsException' );
		$this->file->store();
		$this->file->setContent( -2, 'new content' );
	}

	/**
	 */
	public function testSetContentException3(){
		$this->expectException( 'OutOfRangeException' );
		$this->file->store();
		$this->file->setContent( 2, 'new content' );
	}

	public function testStore(){
		$this->file->store();
		self::assertTrue( file_exists( $this->filePath.'~' ) );
		self::assertEquals( $this->time, file_get_contents( $this->filePath.'~' ) );
		self::assertEquals( filemtime( $this->filePath ), filemtime( $this->filePath.'~' ) );

		$this->file->store();
		self::assertTrue( file_exists( $this->filePath.'.~1~' ) );
		self::assertEquals( $this->time, file_get_contents( $this->filePath.'.~1~' ) );

		$this->file->store();
		self::assertTrue( file_exists( $this->filePath.'.~2~' ) );
		self::assertEquals( $this->time, file_get_contents( $this->filePath.'.~2~' ) );
	}

	public function testStoreWithPreservedTimestamp(){
		$timestamp	= filemtime( $this->filePath ) + 10;
		$result		= touch( $this->filePath, $timestamp );
		clearstatcache();
		self::assertTrue( $result );
		self::assertEquals( $timestamp, filemtime( $this->filePath ) );
	}

	/**
	 */
	public function testStoreException1(){
		$this->expectException( 'RuntimeException' );
		unlink( $this->filePath );
		$this->file->store();
	}
}

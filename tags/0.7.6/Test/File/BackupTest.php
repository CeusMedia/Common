<?php
/**
 *	Test class for File_Backup.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
require_once 'Test/initLoaders.php5';
/**
 *	Test class for File_Backup.
 *	@package		Tests.file
 *	@extends		Test_Case
 *	@uses			File_Editor
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@version		0.1
 */
class Test_File_BackupTest extends Test_Case{

	protected $filePath;
	protected $path;
	protected $time;
	
	public function setUp(){
		$this->time	= time();
		$this->path	= "test";
		$this->filePath	= $this->path."/test.txt";

		if( !file_exists( $this->path ) )
			mkdir( $this->path );
		file_put_contents( $this->filePath, $this->time );
		$this->file	= new File_Backup( $this->filePath );
	}

	public function tearDown(){
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
		$this->assertEquals( "change-0", $this->file->getContent( 1 ) );
		$this->assertEquals( "change-0", $this->file->getContent( -1 ) );
		$this->assertEquals( $this->time, $this->file->getContent( 0 ) );
	}

	/**
	 *	@expectedException		InvalidArgumentException
	 */
	public function testGetContentException1(){
		$this->file->store();
		$this->file->getContent( "a" );
	}

	/**
	 *	@expectedException		OutOfBoundsException
	 */
	public function testGetContentException2(){
		$this->file->store();
		$this->file->getContent( -2 );
	}

	/**
	 *	@expectedException		OutOfRangeException
	 */
	public function testGetContentException3(){
		$this->file->store();
		$this->file->getContent( 1 );
	}

	public function testGetVersions1(){
		$this->assertEquals( array(), $this->file->getVersions() );
	}

	public function testGetVersions2(){
		$this->file->store();
		$this->assertEquals( array( 0 ), array_keys( $this->file->getVersions() ) );
	}

	public function testGetVersions3(){
		$this->file->store();
		$this->file->store();
		$this->assertEquals( array( 0, 1 ), array_keys( $this->file->getVersions() ) );
	}

	public function testMove(){
		$this->file->store();
		$this->file->store();

		$this->assertTrue( file_exists( $this->filePath ) );
		$this->assertTrue( file_exists( $this->filePath.'~' ) );
		$this->assertTrue( file_exists( $this->filePath.'.~1~' ) );

		$target	= $this->path.'text.txt';
		$this->file->move( $target );
		$this->assertTrue( file_exists( $target ) );
		$this->assertTrue( file_exists( $target.'~' ) );
		$this->assertTrue( file_exists( $target.'.~1~' ) );

		$this->assertFalse( file_exists( $this->filePath ) );
		$this->assertFalse( file_exists( $this->filePath.'~' ) );
		$this->assertFalse( file_exists( $this->filePath.'.~1~' ) );

		$this->file->move( $this->filePath );
	}

	/**
	 *	@expectedException		RuntimeException
	 */
	public function testMoveException(){
		$this->file->move( $this->path.'notexistingFolder/filename.test' );
	}

	public function testRemove1(){
		$this->file->store();
		$this->assertTrue( file_exists( $this->filePath.'~' ) );
		$this->file->remove();
		$this->assertTrue( !file_exists( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->remove( -1 );
		$this->assertTrue( !file_exists( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->store();
		$this->file->remove( -1 );
		$this->file->remove( -1 );
		$this->assertTrue( !file_exists( $this->filePath.'~' ) );
	}

	public function testRemove2(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();

		$this->file->remove( 1 );
		$this->assertEquals( "change-2", file_get_contents( $this->filePath.'.~1~' ) );
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
		$this->assertEquals( "change-1", file_get_contents( $this->filePath.'~' ) );

		$this->file->remove( 0 );
		$this->assertEquals( "change-2", file_get_contents( $this->filePath.'~' ) );
	}

	/**
	 *	@expectedException		InvalidArgumentException
	 */
	public function testRemoveException1(){
		$this->file->remove( "a" );
	}

	/**
	 *	@expectedException		OutOfBoundsException
	 */
	public function testRemoveException2(){
		$this->file->remove( -2 );
	}

	/**
	 *	@expectedException		OutOfRangeException
	 */
	public function testRemoveException3(){
		$this->file->remove( 4 );
	}

	/**
	 *	@expectedException		OutOfRangeException
	 */
	public function testRemoveException4(){
		$this->file->remove();
	}

	public function testRestore1(){
		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		$this->file->restore();
		$this->assertEquals( $this->time, file_get_contents( $this->filePath ) );
		$this->assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		$this->file->restore( -1 );
		$this->assertEquals( $this->time, file_get_contents( $this->filePath ) );
		$this->assertTrue( file_exists( $this->filePath.'~' ) );
	}

	public function testRestore2(){
		$this->file->store();
		file_put_contents( $this->filePath, "changed" );
		$this->assertEquals( "changed", file_get_contents( $this->filePath ) );

		$this->file->restore( -1, TRUE );
		$this->assertEquals( $this->time, file_get_contents( $this->filePath ) );
		$this->assertTrue( !file_exists( $this->filePath.'~' ) );
	}

	public function testRestore3(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-3" );
		$this->assertEquals( $this->file->getVersion(), 2 );

		$this->file->restore( 1 );
		$this->assertEquals( "change-1", file_get_contents( $this->filePath ) );
	}

	public function testRestoreWithRemoveForwards(){
		file_put_contents( $this->filePath, "change-0" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-1" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-2" );
		$this->file->store();
		file_put_contents( $this->filePath, "change-3" );
		$this->assertEquals( $this->file->getVersion(), 2 );

		$this->file->restore( 0, TRUE );
		$this->assertEquals( "change-0", file_get_contents( $this->filePath ) );
		$this->assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->restore( 0, TRUE );
		$this->assertEquals( "change-1", file_get_contents( $this->filePath ) );
		$this->assertTrue( file_exists( $this->filePath.'~' ) );

		$this->file->restore( 0, TRUE );
		$this->assertEquals( "change-2", file_get_contents( $this->filePath ) );
		$this->assertFalse( file_exists( $this->filePath.'~' ) );
	}

	public function testRestoreWithPreservedTimestamp(){
		$timestamp	= filemtime( $this->filePath ) - 5;
		touch( $this->filePath, $timestamp );
		clearstatcache();
		$this->file->store();
		$this->assertEquals( $timestamp, filemtime( $this->filePath.'~' ) );

		$this->file->restore();
		$this->assertEquals( filemtime( $this->filePath ), $timestamp );
	}

	/**
	 *	@expectedException		InvalidArgumentException
	 */
	public function testRestoreException1(){
		$this->file->restore( "a" );
	}

	/**
	 *	@expectedException		OutOfBoundsException
	 */
	public function testRestoreException2(){
		$this->file->restore( -2 );
	}

	/**
	 *	@expectedException		RuntimeException
	 */
	public function testRestoreException3(){
		$this->file->store();
		$this->tearDown();
		$this->file->restore();
	}

	public function testSetContent(){
		$this->file->store();
		$this->file->setContent( 0, 'new content' );
		$this->assertEquals( 'new content', file_get_contents( $this->filePath.'~' ) );

		$this->file->store();
		$this->file->setContent( 1, 'even newer content' );
		$this->assertEquals( 'even newer content', file_get_contents( $this->filePath.'.~1~' ) );
	}

	/**
	 *	@expectedException		InvalidArgumentException
	 */
	public function testSetContentException1(){
		$this->file->store();
		$this->file->setContent( 'wrong', 'new content' );
	}

	/**
	 *	@expectedException		OutOfBoundsException
	 */
	public function testSetContentException2(){
		$this->file->store();
		$this->file->setContent( -2, 'new content' );
	}

	/**
	 *	@expectedException		OutOfRangeException
	 */
	public function testSetContentException3(){
		$this->file->store();
		$this->file->setContent( 2, 'new content' );
	}

	public function testStore(){
		$this->file->store();
		$this->assertTrue( file_exists( $this->filePath.'~' ) );
		$this->assertEquals( $this->time, file_get_contents( $this->filePath.'~' ) );
		$this->assertEquals( filemtime( $this->filePath ), filemtime( $this->filePath.'~' ) );

		$this->file->store();
		$this->assertTrue( file_exists( $this->filePath.'.~1~' ) );
		$this->assertEquals( $this->time, file_get_contents( $this->filePath.'.~1~' ) );

		$this->file->store();
		$this->assertTrue( file_exists( $this->filePath.'.~2~' ) );
		$this->assertEquals( $this->time, file_get_contents( $this->filePath.'.~2~' ) );
	}

	public function testStoreWithPreservedTimestamp(){
		$timestamp	= filemtime( $this->filePath ) + 10;
		$result		= touch( $this->filePath, $timestamp );
		clearstatcache();
		$this->assertTrue( $result );
		$this->assertEquals( $timestamp, filemtime( $this->filePath ) );
	}

	/**
	 *	@expectedException		RuntimeException
	 */
	public function testStoreException1(){
		unlink( $this->filePath );
		$this->file->store();
	}
}
?>

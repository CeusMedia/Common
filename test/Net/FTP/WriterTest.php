<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\FTP;

use CeusMedia\CommonTest\BaseCase;
use CeusMedia\Common\Net\FTP\Connection;
use CeusMedia\Common\Net\FTP\Reader;
use CeusMedia\Common\Net\FTP\Writer;

/**
 *	TestUnit of Net_FTP_Writer.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class WriterTest extends BaseCase
{
	/** @var ?Connection  */
	protected ?Connection $connection	= NULL;

	protected string $local;

	protected string $path;

	protected ?Reader $reader	= NULL;

	protected ?Writer $writer	= NULL;

	/**
	 *	Tests Method 'changeRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeRights()
	{
		$this->checkFtpConfig();
		file_put_contents( $this->local."rightsTest", "this file will be removed" );
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) != "WIN" )
		{
			$assertion	= 0777;
			$creation	= $this->writer->changeRights( "rightsTest", 0777 );
			$this->assertEquals( $assertion, $creation );
		}
	}

	/**
	 *	Tests Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->copyFile( "source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."target.txt" );

		$creation	= $this->writer->copyFile( "folder/source.txt", "folder/target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."folder/target.txt" );
	}

	/**
	 *	Tests Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileInPath()
	{
		$this->checkFtpConfig();
		$this->writer->setPath( "folder" );

		$creation	= $this->writer->copyFile( "source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."folder/target.txt" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
		$this->checkFtpConfig();
		$this->expectException( 'RuntimeException' );
		$this->writer->copyFile( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException2()
	{
		$this->checkFtpConfig();
		$this->expectException( 'RuntimeException' );
		$this->writer->copyFile( "source.txt", "not_existing/not_relevant.txt" );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->copyFolder( "folder", "copy" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."copy" );

		$assertion	= 1;
		$creation	= count( $this->reader->getFileList( "copy" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'createFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->createFolder( "created" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."created" );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$this->checkFtpConfig();
		$assertion	= preg_replace( '/^(.+)\/$/', '\\1', "/".$this->path );
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$this->writer->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->moveFile( "source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."target.txt" );
		$this->assertFileDoesNotExist( $this->local."source.txt" );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->moveFolder( "folder", "moved" );
		$this->assertTrue( $creation );
		$this->assertFileDoesNotExist( $this->local."folder" );
		$this->assertFileExists( $this->local."moved" );

		$creation	= $this->writer->moveFolder( "moved", "moved" );
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'putFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPutFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->putFile( $this->local."source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."target.txt" );

		$assertion	= "source file";
		$creation	= file_get_contents( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->removeFile( "folder/source.txt" );
		$this->assertTrue( $creation );
		$this->assertFileDoesNotExist( $this->local."folder/source.txt" );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->removeFolder( "folder" );
		$this->assertTrue( $creation );
		$this->assertFileDoesNotExist( $this->local."folder" );
	}

	/**
	 *	Tests Method 'renameFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->renameFile( "source.txt", "renamed.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."renamed.txt" );
		$this->assertFileDoesNotExist( $this->local."source.txt" );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$this->checkFtpConfig();
		$creation	= $this->writer->setPath( "not_existing" );
		$this->assertFalse( $creation );

		$creation	= $this->writer->setPath( "folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->writer->setPath( "/".$this->path."folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->writer->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	@param		bool	$markSkipped		Flag: Mark test as skipped, default: yes;
	 *	@return		bool
	 */
	protected function checkFtpConfig( bool $markSkipped = TRUE ): bool
	{
		if( NULL !== $this->writer )
			return TRUE;
		if( $markSkipped )
			$this->markTestSkipped( 'No FTP data set in cmClasses.ini' );
		return FALSE;
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$config		= self::$_config['unitTest-FTP'] ?? [];
		$host		= $config['host'] ?? NULL;
		$port		= (int) ( $config['port'] ?? 0 );
		$username	= $config['user'] ?? NULL;
		$password	= $config['pass'] ?? NULL;

		$this->path		= $config['path'] ?? NULL;
		$this->local	= $config['local'] ?? '';

		if( '' === $this->local )
			return;

		$this->connection	= new Connection( $host, $port );
		$this->connection->login( $username, $password );

		@mkDir( $this->local );
		@mkDir( $this->local."folder" );
		@file_put_contents( $this->local."source.txt", "source file" );
		@file_put_contents( $this->local."folder/source.txt", "source file" );

		if( $this->path )
			$this->connection->setPath( $this->path );

		$this->reader	= new Reader( $this->connection );
		$this->writer	= new Writer( $this->connection );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->local."source.txt" );
		@unlink( $this->local."target.txt" );
		@unlink( $this->local."renamed.txt" );
		@unlink( $this->local."folder/source.txt" );
		@unlink( $this->local."folder/target.txt" );
		@unlink( $this->local."copy/source.txt" );
		@unlink( $this->local."moved/source.txt" );
		@unlink( $this->local."rightsTest" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local."copy" );
		@rmDir( $this->local."created" );
		@rmDir( $this->local."moved" );
		@rmDir( $this->local );
		$this->connection->close();
	}
}

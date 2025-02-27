<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\FTP;

use CeusMedia\Common\Net\FTP\Client;
use CeusMedia\Common\Net\FTP\Connection;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ClientTest extends BaseCase
{
	/** @var ?Client  */
	protected ?Client $client			= NULL;

	/** @var ?Connection */
	protected ?Connection $connection	= NULL;

	protected ?string $host;

	protected ?int $port;

	protected string $username;

	protected string $password;

	protected string $local;

	protected string $path;

	protected array $config;

	protected function login(): void
	{
		$this->connection->login( $this->username, $this->password );
		if( $this->path )
			$this->connection->setPath( $this->path );
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$this->config	= self::$_config['unitTest-FTP'] ?? [];
		$this->host		= $this->config['host'] ?? NULL;
		$this->port		= (int) ( $this->config['port'] ?? 0 );
		$this->username	= $this->config['user'] ?? NULL;
		$this->password	= $this->config['pass'] ?? NULL;
		$this->path		= $this->config['path'] ?? NULL;
		$this->local	= $this->config['local'] ?? '';

		if( '' === $this->local )
			return;

		@mkDir( $this->local );
		@mkDir( $this->local."folder" );
		@mkDir( $this->local."folder/nested" );
		@file_put_contents( $this->local."test1.txt", "test1" );
		@file_put_contents( $this->local."test2.txt", "test2" );
		@file_put_contents( $this->local."folder/test3.txt", "test3" );
		@file_put_contents( $this->local."folder/test4.txt", "test4" );
		@file_put_contents( $this->local."source.txt", "source file" );
		@file_put_contents( $this->local."folder/source.txt", "source file" );

		$this->client	= new Client( $this->host, $this->port, $this->username, $this->password );
		if( $this->path )
			$this->client->setPath( $this->path );
	}

	/**
	 *	@param		bool	$markSkipped		Flag: Mark test as skipped, default: yes;
	 *	@return		bool
	 */
	protected function checkFtpConfig( bool $markSkipped = TRUE ): bool
	{
		if( NULL !== $this->client )
			return TRUE;
		if( $markSkipped )
			$this->markTestSkipped( 'No FTP data set in cmClasses.ini' );
		return FALSE;
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		if( !$this->local )
			return;
		@unlink( $this->local."test1.txt" );
		@unlink( $this->local."test2.txt" );
		@unlink( $this->local."folder/test3.txt" );
		@unlink( $this->local."folder/test4.txt" );
		@unlink( $this->local."source.txt" );
		@unlink( $this->local."target.txt" );
		@unlink( $this->local."renamed.txt" );
		@unlink( $this->local."folder/source.txt" );
		@unlink( $this->local."folder/target.txt" );
		@unlink( $this->local."copy/source.txt" );
		@unlink( $this->local."copy/test3.txt" );
		@unlink( $this->local."copy/test4.txt" );
		@unlink( $this->local."moved/source.txt" );
		@unlink( $this->local."moved/test3.txt" );
		@unlink( $this->local."moved/test4.txt" );
		@unlink( $this->local."rightsTest" );
		@rmDir( $this->local."copy/nested" );
		@rmDir( $this->local."copy" );
		@rmDir( $this->local."created" );
		@rmDir( $this->local."moved/nested" );
		@rmDir( $this->local."moved" );
		@rmDir( $this->local."folder/nested" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local );
	}

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
			$creation	= $this->client->changeRights( "rightsTest", 0777 );
			self::assertEquals( $assertion, $creation );
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
		$creation	= $this->client->copyFile( "source.txt", "target.txt" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."target.txt" );

		$creation	= $this->client->copyFile( "folder/source.txt", "folder/target.txt" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."folder/target.txt" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
		if( !$this->checkFtpConfig() )
			return;
		$this->expectException( 'RuntimeException' );
		$this->client->copyFile( "not_existing", "not_relevant" );
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
		$this->client->copyFile( "source.txt", "not_existing/not_relevant.txt" );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->copyFolder( "folder", "copy" );
		self::assertTrue( $creation );

		self::assertFileExists( $this->local."copy" );

		$assertion	= 3;
		$creation	= count( $this->client->getFileList( "copy" ) );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'createFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->createFolder( "created" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."created" );
	}

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->getFile( "test1.txt", "test_getFile" );
		self::assertTrue( $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		self::assertEquals( $assertion, $creation );

		$creation	= $this->client->getFile( "folder/test3.txt", "test_getFile" );
		self::assertTrue( $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		self::assertEquals( $assertion, $creation );

		$creation	= $this->client->getFile( "not_existing", "test_getFile" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$this->checkFtpConfig();
		$files		= $this->client->getFileList( "folder" );
		$assertion	= 3;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );

		$assertion	= "source.txt";
		$creation	= $files[0]['name'];
		self::assertEquals( $assertion, $creation );

		$assertion	= "test3.txt";
		$creation	= $files[1]['name'];
		self::assertEquals( $assertion, $creation );

		$assertion	= "test4.txt";
		$creation	= $files[2]['name'];
		self::assertEquals( $assertion, $creation );

		$files		= $this->client->getFileList( "", TRUE );
		$assertion	= 6;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderList()
	{
		$this->checkFtpConfig();
		$folders	= $this->client->getFolderList();
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->client->getFolderList( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "nested";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->client->getFolderList( "", TRUE );
		$assertion	= 2;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$this->checkFtpConfig();
		$files		= [];
		$list		= $this->client->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->client->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->client->getList( "folder" );
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'nested', 'source.txt', 'test3.txt', 'test4.txt' );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );
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
		$creation	= $this->client->getPath();
		self::assertEquals( $assertion, $creation );

		$this->client->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPermissionsAsOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPermissionsAsOctal()
	{
		$this->checkFtpConfig();
		$assertion	= '0777';
		$creation	= $this->client->getPermissionsAsOctal( "drwxrwxrwx" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0000';
		$creation	= $this->client->getPermissionsAsOctal( "d---------" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0751';
		$creation	= $this->client->getPermissionsAsOctal( "drwxr-x--x" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0642';
		$creation	= $this->client->getPermissionsAsOctal( "drw-r---w-" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->moveFile( "source.txt", "target.txt" );
		self::assertTrue( $creation );
		self::assertFileDoesNotExist( $this->local."source.txt" );
		self::assertFileExists( $this->local."target.txt" );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->moveFolder( "folder", "moved" );
		self::assertTrue( $creation );
		self::assertFileDoesNotExist( $this->local."folder" );
		self::assertFileExists( $this->local."moved" );

		$creation	= $this->client->moveFolder( "moved", "moved" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."moved" );
	}

	/**
	 *	Tests Method 'putFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPutFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->putFile( $this->local."source.txt", "target.txt" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."target.txt" );

		$assertion	= "source file";
		$creation	= file_get_contents( $this->local."target.txt" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->removeFile( "folder/source.txt" );
		self::assertTrue( $creation );
		self::assertFileDoesNotExist( $this->local."folder/source.txt" );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->removeFolder( "folder" );
		self::assertTrue( $creation );
		self::assertFileDoesNotExist( $this->local."folder" );
	}

	/**
	 *	Tests Method 'renameFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->renameFile( "source.txt", "renamed.txt" );
		self::assertTrue( $creation );
		self::assertFileExists( $this->local."renamed.txt" );
	}

	/**
	 *	Tests Method 'searchFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFile()
	{
		$this->checkFtpConfig();
		$files		= $this->client->searchFile( "test1.txt" );
		$assertion	= 1;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );

		$assertion	= "test1.txt";
		$creation	= $files[0]['name'];
		self::assertEquals( $assertion, $creation );

		$files		= $this->client->searchFile( "@\.txt$@", TRUE, TRUE );
		$assertion	= 6;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'searchFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFolder()
	{
		$this->checkFtpConfig();
		$folders	= $this->client->searchFolder( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->client->searchFolder( "@e@", TRUE, TRUE );
		$assertion	= 2;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$names		= [];
		foreach( $folders as $folder )
			$names[]	= $folder['name'];
		$assertion	= array( "folder", "folder/nested" );;
		$creation	= $names;
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$this->checkFtpConfig();
		$creation	= $this->client->setPath( "not_existing" );
		self::assertFalse( $creation );

		$creation	= $this->client->setPath( "folder" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		self::assertEquals( $assertion, $creation );

		$creation	= $this->client->setPath( "/".$this->path."folder" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		self::assertEquals( $assertion, $creation );

		$creation	= $this->client->setPath( "/".$this->path."folder/nested" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder/nested";
		$creation	= $this->client->getPath();
		self::assertEquals( $assertion, $creation );
	}
}

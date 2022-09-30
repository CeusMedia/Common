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
	/** @var Client  */
	protected $client;

	/** @var Connection */
	protected $connection;

	protected $host;

	protected $port;

	protected $username;

	protected $password;

	protected $local;

	protected $path;

	protected $config;

	protected function login() {
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
		$this->config	= self::$_config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in Common.ini' );

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
		file_put_contents( $this->local."rightsTest", "this file will be removed" );
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) != "WIN" )
		{
			$assertion	= 0777;
			$creation	= $this->client->changeRights( "rightsTest", 0777 );
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
		$creation	= $this->client->copyFile( "source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."target.txt" );

		$creation	= $this->client->copyFile( "folder/source.txt", "folder/target.txt" );
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
		$creation	= $this->client->copyFolder( "folder", "copy" );
		$this->assertTrue( $creation );

		$this->assertFileExists( $this->local."copy" );

		$assertion	= 3;
		$creation	= count( $this->client->getFileList( "copy" ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'createFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCreateFolder()
	{
		$creation	= $this->client->createFolder( "created" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."created" );
	}

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
		$creation	= $this->client->getFile( "test1.txt", "test_getFile" );
		$this->assertTrue( $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->client->getFile( "folder/test3.txt", "test_getFile" );
		$this->assertTrue( $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->client->getFile( "not_existing", "test_getFile" );
		$this->assertFalse( $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$files		= $this->client->getFileList( "folder" );
		$assertion	= 3;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "source.txt";
		$creation	= $files[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test3.txt";
		$creation	= $files[1]['name'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test4.txt";
		$creation	= $files[2]['name'];
		$this->assertEquals( $assertion, $creation );

		$files		= $this->client->getFileList( "", TRUE );
		$assertion	= 6;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFolderList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFolderList()
	{
		$folders	= $this->client->getFolderList();
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->client->getFolderList( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "nested";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->client->getFolderList( "", TRUE );
		$assertion	= 2;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetList()
	{
		$files		= [];
		$list		= $this->client->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->client->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->client->getList( "folder" );
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'nested', 'source.txt', 'test3.txt', 'test4.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPath()
	{
		$assertion	= preg_replace( '/^(.+)\/$/', '\\1', "/".$this->path );
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$this->client->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPermissionsAsOctal'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPermissionsAsOctal()
	{
		$assertion	= '0777';
		$creation	= $this->client->getPermissionsAsOctal( "drwxrwxrwx" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0000';
		$creation	= $this->client->getPermissionsAsOctal( "d---------" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0751';
		$creation	= $this->client->getPermissionsAsOctal( "drwxr-x--x" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0642';
		$creation	= $this->client->getPermissionsAsOctal( "drw-r---w-" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFile()
	{
		$creation	= $this->client->moveFile( "source.txt", "target.txt" );
		$this->assertTrue( $creation );
		$this->assertFileDoesNotExist( $this->local."source.txt" );
		$this->assertFileExists( $this->local."target.txt" );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$creation	= $this->client->moveFolder( "folder", "moved" );
		$this->assertTrue( $creation );
		$this->assertFileDoesNotExist( $this->local."folder" );
		$this->assertFileExists( $this->local."moved" );

		$creation	= $this->client->moveFolder( "moved", "moved" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."moved" );
	}

	/**
	 *	Tests Method 'putFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPutFile()
	{
		$creation	= $this->client->putFile( $this->local."source.txt", "target.txt" );
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
		$creation	= $this->client->removeFile( "folder/source.txt" );
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
		$creation	= $this->client->removeFolder( "folder" );
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
		$creation	= $this->client->renameFile( "source.txt", "renamed.txt" );
		$this->assertTrue( $creation );
		$this->assertFileExists( $this->local."renamed.txt" );
	}

	/**
	 *	Tests Method 'searchFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFile()
	{
		$files		= $this->client->searchFile( "test1.txt" );
		$assertion	= 1;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test1.txt";
		$creation	= $files[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$files		= $this->client->searchFile( "@\.txt$@", TRUE, TRUE );
		$assertion	= 6;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'searchFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFolder()
	{
		$folders	= $this->client->searchFolder( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->client->searchFolder( "@e@", TRUE, TRUE );
		$assertion	= 2;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$names		= [];
		foreach( $folders as $folder )
			$names[]	= $folder['name'];
		$assertion	= array( "folder", "folder/nested" );;
		$creation	= $names;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$creation	= $this->client->setPath( "not_existing" );
		$this->assertFalse( $creation );

		$creation	= $this->client->setPath( "folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->client->setPath( "/".$this->path."folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->client->setPath( "/".$this->path."folder/nested" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder/nested";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );
	}
}

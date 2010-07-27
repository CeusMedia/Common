<?php
/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Client
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
class Test_Net_FTP_ClientTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$config	= parse_ini_file( CMC_PATH.'../cmClasses.ini', TRUE );
		$this->config	= $config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];
	}

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
	public function setUp()
	{
		if( !$this->local )
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

		$this->client	= new Net_FTP_Client( $this->host, $this->port, $this->username, $this->password );
		if( $this->path )
			$this->client->setPath( $this->path );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		if( $this->local )
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		file_put_contents( $this->local."rightsTest", "this file will be removed" );
		if( strtoupper( substr( PHP_OS, 0, 3 ) ) != "WIN" )
		{
			$assertion	= TRUE;
			$creation	= $this->client->changeRights( "rightsTest", 777 );
			$this->assertEquals( $assertion, $creation );

			$file		= $this->client->getFile( "rightsTest" );
			$assertion	= 777;
			$creation	= $file['octal'];
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->copyFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->copyFile( "folder/source.txt", "folder/target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."folder/target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$this->setExpectedException( 'RuntimeException' );
		$this->client->copyFile( "not_existing", "not_relevant" );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException2()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$this->setExpectedException( 'RuntimeException' );
		$this->client->copyFile( "source.txt", "not_existing/not_relevant.txt" );
	}

	/**
	 *	Tests Method 'copyFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFolder()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->copyFolder( "folder", "copy" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."copy" );
		$this->assertEquals( $assertion, $creation );

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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->createFolder( "created" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."created" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->getFile( "test1.txt", "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->getFile( "folder/test3.txt", "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->client->getFile( "not_existing", "test_getFile" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$files		= array();
		$list		= $this->client->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= array();
		$list		= $this->client->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'source.txt', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= array();
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= "/";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );
		
		$this->client->setPath( "folder" );

		$assertion	= "/folder";
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= 777;
		$creation	= $this->client->getPermissionsAsOctal( "drwxrwxrwx" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 000;
		$creation	= $this->client->getPermissionsAsOctal( "d---------" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 751;
		$creation	= $this->client->getPermissionsAsOctal( "drwxr-x--x" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 642;
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->moveFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->local."source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->moveFolder( "folder", "moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->local."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->client->moveFolder( "moved", "moved" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'putFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testPutFile()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->putFile( $this->local."source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."target.txt" );
		$this->assertEquals( $assertion, $creation );

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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->removeFile( "folder/source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->local."folder/source.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->removeFolder( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->local."folder" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'renameFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFile()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= TRUE;
		$creation	= $this->client->renameFile( "source.txt", "renamed.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->local."renamed.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'searchFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFile()
	{
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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

		$names		= array();
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
		if( $this->local )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$assertion	= FALSE;
		$creation	= $this->client->setPath( "not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->setPath( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->setPath( "/folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->setPath( "/folder/nested" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder/nested";
		$creation	= $this->client->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->client->setPath( "folder/nested" );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
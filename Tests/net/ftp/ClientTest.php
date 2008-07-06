<?php
/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Client
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.ftp.Client' );
/**
 *	TestUnit of Net_FTP_Client.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Client
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
class Tests_Net_FTP_ClientTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->host		= "localhost";
		$this->port		= 21;
		$this->username	= "ftp_user";
		$this->password	= "ftp_pass";
		$this->ftpPath	= dirname( __FILE__ )."/upload/";
	}

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		@mkDir( $this->ftpPath );
		@mkDir( $this->ftpPath."folder" );
		@mkDir( $this->ftpPath."folder/nested" );
		@file_put_contents( $this->ftpPath."test1.txt", "test1" );
		@file_put_contents( $this->ftpPath."test2.txt", "test2" );
		@file_put_contents( $this->ftpPath."folder/test3.txt", "test3" );
		@file_put_contents( $this->ftpPath."folder/test4.txt", "test4" );
		@file_put_contents( $this->ftpPath."source.txt", "source file" );
		@file_put_contents( $this->ftpPath."folder/source.txt", "source file" );

		$this->client	= new Net_FTP_Client( $this->host, $this->port, $this->username, $this->password );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->ftpPath."test1.txt" );
		@unlink( $this->ftpPath."test2.txt" );
		@unlink( $this->ftpPath."folder/test3.txt" );
		@unlink( $this->ftpPath."folder/test4.txt" );
		@unlink( $this->ftpPath."source.txt" );
		@unlink( $this->ftpPath."target.txt" );
		@unlink( $this->ftpPath."renamed.txt" );
		@unlink( $this->ftpPath."folder/source.txt" );
		@unlink( $this->ftpPath."folder/target.txt" );
		@unlink( $this->ftpPath."copy/source.txt" );
		@unlink( $this->ftpPath."copy/test3.txt" );
		@unlink( $this->ftpPath."copy/test4.txt" );
		@unlink( $this->ftpPath."moved/source.txt" );
		@unlink( $this->ftpPath."moved/test3.txt" );
		@unlink( $this->ftpPath."moved/test4.txt" );
		@unlink( $this->ftpPath."rightsTest" );
		@rmDir( $this->ftpPath."copy/nested" );
		@rmDir( $this->ftpPath."copy" );
		@rmDir( $this->ftpPath."created" );
		@rmDir( $this->ftpPath."moved/nested" );
		@rmDir( $this->ftpPath."moved" );
		@rmDir( $this->ftpPath."folder/nested" );
		@rmDir( $this->ftpPath."folder" );
		@rmDir( $this->ftpPath );
	}

	/**
	 *	Tests Method 'changeRights'.
	 *	@access		public
	 *	@return		void
	 */
	public function testChangeRights()
	{
		file_put_contents( $this->ftpPath."rightsTest", "this file will be removed" );
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
		$assertion	= TRUE;
		$creation	= $this->client->copyFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->client->copyFile( "folder/source.txt", "folder/target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."folder/target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'copyFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCopyFileException1()
	{
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
		$assertion	= TRUE;
		$creation	= $this->client->copyFolder( "folder", "copy" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."copy" );
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
		$assertion	= TRUE;
		$creation	= $this->client->createFolder( "created" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."created" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
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
		$assertion	= TRUE;
		$creation	= $this->client->moveFile( "source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'moveFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testMoveFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->client->moveFolder( "folder", "moved" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."moved" );
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
		$assertion	= TRUE;
		$creation	= $this->client->putFile( $this->ftpPath."source.txt", "target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "source file";
		$creation	= file_get_contents( $this->ftpPath."target.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFile()
	{
		$assertion	= TRUE;
		$creation	= $this->client->removeFile( "folder/source.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder/source.txt" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'removeFolder'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRemoveFolder()
	{
		$assertion	= TRUE;
		$creation	= $this->client->removeFolder( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= file_exists( $this->ftpPath."folder" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'renameFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRenameFile()
	{
		$assertion	= TRUE;
		$creation	= $this->client->renameFile( "source.txt", "renamed.txt" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->ftpPath."renamed.txt" );
		$this->assertEquals( $assertion, $creation );
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
<?php
/**
 *	TestUnit of Net_FTP_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Net_FTP_Reader.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			Net_FTP_Connection
 *	@uses			Net_FTP_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Test_Net_FTP_ReaderTest extends Test_Case
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$config	= parse_ini_file( self::$pathLib.'Common.ini', TRUE );
		$this->config	= $config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in Common.ini' );

		$this->connection	= new Net_FTP_Connection( $this->host, $this->port );
		$this->connection->login( $this->username, $this->password );

		@unlink( $this->local."test1.txt" );
		@unlink( $this->local."test2.txt" );
		@unlink( $this->local."folder/test3.txt" );
		@unlink( $this->local."folder/test4.txt" );
		@rmDir( $this->local."folder/nested" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local );

		@mkDir( $this->local );
		@mkDir( $this->local."folder" );
		@mkDir( $this->local."folder/nested" );
		@file_put_contents( $this->local."test1.txt", "test1" );
		@file_put_contents( $this->local."test2.txt", "test2" );
		@file_put_contents( $this->local."folder/test3.txt", "test3" );
		@file_put_contents( $this->local."folder/test4.txt", "test4" );

		if( $this->path )
			$this->connection->setPath( $this->path );

		$this->reader	= new Net_FTP_Reader( $this->connection );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		if( !$this->local )
			return;
		@unlink( $this->local."test1.txt" );
		@unlink( $this->local."test2.txt" );
		@unlink( $this->local."folder/test3.txt" );
		@unlink( $this->local."folder/test4.txt" );
		@rmDir( $this->local."folder/nested" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local );
		$this->connection->close();
	}

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
		$assertion	= TRUE;
		$creation	= $this->reader->getFile( "test1.txt", "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->reader->getFile( "folder/test3.txt", "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->reader->getFile( "not_existing", "test_getFile" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFileList'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFileList()
	{
		$files		= $this->reader->getFileList( "folder" );
		$assertion	= 2;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test3.txt";
		$creation	= $files[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test4.txt";
		$creation	= $files[1]['name'];
		$this->assertEquals( $assertion, $creation );

		$files		= $this->reader->getFileList( "", TRUE );
		$assertion	= 4;
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
		$folders	= $this->reader->getFolderList();
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->reader->getFolderList( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "nested";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->reader->getFolderList( "", TRUE );
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
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= array();
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= array();
		$list		= $this->reader->getList( "folder" );
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'nested', 'test3.txt', 'test4.txt' );
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
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$this->reader->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
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
		$creation	= $this->reader->getPermissionsAsOctal( "drwxrwxrwx" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0000';
		$creation	= $this->reader->getPermissionsAsOctal( "d---------" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0751';
		$creation	= $this->reader->getPermissionsAsOctal( "drwxr-x--x" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= '0642';
		$creation	= $this->reader->getPermissionsAsOctal( "drw-r---w-" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'searchFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFile()
	{
		$files		= $this->reader->searchFile( "test1.txt" );
		$assertion	= 1;
		$creation	= count( $files );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "test1.txt";
		$creation	= $files[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$files		= $this->reader->searchFile( "@\.txt$@", TRUE, TRUE );
		$assertion	= 4;
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
		$folders	= $this->reader->searchFolder( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		$this->assertEquals( $assertion, $creation );

		$folders	= $this->reader->searchFolder( "@e@", TRUE, TRUE );
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
		$creation	= $this->reader->setPath( "not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->reader->setPath( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->reader->setPath( "/".$this->path."folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->reader->setPath( "/".$this->path."folder/nested" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/".$this->path."folder/nested";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );
	}
}

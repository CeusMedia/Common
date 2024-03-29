<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_FTP_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\FTP;

use CeusMedia\Common\Net\FTP\Connection;
use CeusMedia\Common\Net\FTP\Reader;
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_FTP_Reader.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ReaderTest extends BaseCase
{
	/** @var Connection  */
	protected $connection;

	protected $local;

	protected $path;

	protected $reader;

	protected $writer;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		$config	= self::$_config['unitTest-FTP'];
		$host		= $config['host'];
		$port		= $config['port'];
		$username	= $config['user'];
		$password	= $config['pass'];
		$this->path		= $config['path'];
		$this->local	= $config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in Common.ini' );

		$this->connection	= new Connection( $host, $port );
		$this->connection->login( $username, $password );

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

		$this->reader	= new Reader( $this->connection );
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
		$creation	= $this->reader->getFile( "test1.txt", "test_getFile" );
		$this->assertTrue( $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->reader->getFile( "folder/test3.txt", "test_getFile" );
		$this->assertTrue( $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->reader->getFile( "not_existing", "test_getFile" );
		$this->assertFalse( $creation );
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
		$files		= [];
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		$this->assertEquals( $assertion, $creation );

		$files		= [];
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
		$creation	= $this->reader->setPath( "not_existing" );
		$this->assertFalse( $creation );

		$creation	= $this->reader->setPath( "folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->reader->setPath( "/".$this->path."folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );

		$creation	= $this->reader->setPath( "/".$this->path."folder/nested" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder/nested";
		$creation	= $this->reader->getPath();
		$this->assertEquals( $assertion, $creation );
	}
}

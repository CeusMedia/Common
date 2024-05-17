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
	/** @var ?Connection  */
	protected ?Connection $connection	= NULL;

	protected ?string $local;

	protected ?string $path;

	protected ?Reader $reader	= NULL;

	/**
	 *	Tests Method 'getFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFile()
	{
		$this->checkFtpConfig();
		$creation	= $this->reader->getFile( "test1.txt", "test_getFile" );
		self::assertTrue( $creation );

		$assertion	= "test1";
		$creation	= file_get_contents( "test_getFile" );
		self::assertEquals( $assertion, $creation );

		$creation	= $this->reader->getFile( "folder/test3.txt", "test_getFile" );
		self::assertTrue( $creation );

		$assertion	= "test3";
		$creation	= file_get_contents( "test_getFile" );
		self::assertEquals( $assertion, $creation );

		$creation	= $this->reader->getFile( "not_existing", "test_getFile" );
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
		$files		= $this->reader->getFileList( "folder" );
		$assertion	= 2;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );

		$assertion	= "test3.txt";
		$creation	= $files[0]['name'];
		self::assertEquals( $assertion, $creation );

		$assertion	= "test4.txt";
		$creation	= $files[1]['name'];
		self::assertEquals( $assertion, $creation );

		$files		= $this->reader->getFileList( "", TRUE );
		$assertion	= 4;
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
		$folders	= $this->reader->getFolderList();
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->reader->getFolderList( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "nested";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->reader->getFolderList( "", TRUE );
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
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			if( $entry['isfile'] )
				$files[]	= $entry['name'];
		$assertion	= array( 'test1.txt', 'test2.txt' );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->reader->getList();
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'folder', 'test1.txt', 'test2.txt' );
		$creation	= $files;
		self::assertEquals( $assertion, $creation );

		$files		= [];
		$list		= $this->reader->getList( "folder" );
		foreach( $list as $entry )
			$files[]	= $entry['name'];
		$assertion	= array( 'nested', 'test3.txt', 'test4.txt' );
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
		$creation	= $this->reader->getPath();
		self::assertEquals( $assertion, $creation );

		$this->reader->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
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
		$creation	= $this->reader->getPermissionsAsOctal( "drwxrwxrwx" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0000';
		$creation	= $this->reader->getPermissionsAsOctal( "d---------" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0751';
		$creation	= $this->reader->getPermissionsAsOctal( "drwxr-x--x" );
		self::assertEquals( $assertion, $creation );

		$assertion	= '0642';
		$creation	= $this->reader->getPermissionsAsOctal( "drw-r---w-" );
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'searchFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSearchFile()
	{
		$this->checkFtpConfig();
		$files		= $this->reader->searchFile( "test1.txt" );
		$assertion	= 1;
		$creation	= count( $files );
		self::assertEquals( $assertion, $creation );

		$assertion	= "test1.txt";
		$creation	= $files[0]['name'];
		self::assertEquals( $assertion, $creation );

		$files		= $this->reader->searchFile( "@\.txt$@", TRUE, TRUE );
		$assertion	= 4;
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
		$folders	= $this->reader->searchFolder( "folder" );
		$assertion	= 1;
		$creation	= count( $folders );
		self::assertEquals( $assertion, $creation );

		$assertion	= "folder";
		$creation	= $folders[0]['name'];
		self::assertEquals( $assertion, $creation );

		$folders	= $this->reader->searchFolder( "@e@", TRUE, TRUE );
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
		$creation	= $this->reader->setPath( "not_existing" );
		self::assertFalse( $creation );

		$creation	= $this->reader->setPath( "folder" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		self::assertEquals( $assertion, $creation );

		$creation	= $this->reader->setPath( "/".$this->path."folder" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->reader->getPath();
		self::assertEquals( $assertion, $creation );

		$creation	= $this->reader->setPath( "/".$this->path."folder/nested" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder/nested";
		$creation	= $this->reader->getPath();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	@param		bool	$markSkipped		Flag: Mark test as skipped, default: yes;
	 *	@return		bool
	 */
	protected function checkFtpConfig( bool $markSkipped = TRUE ): bool
	{
		if( NULL !== $this->reader )
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
	protected function setUp(): void
	{
		$config		= self::$_config['unitTest-FTP'] ?? [];
		$host		= $config['host'] ?? NULL;
		$port		= (int) ( $config['port'] ?? 0 );
		$username	= $config['user'] ?? NULL;
		$password	= $config['pass'] ?? NULL;

		$this->path		= $config['path'] ?? NULL;
		$this->local	= $config['local'] ?? '';

		@unlink( $this->local."test1.txt" );
		@unlink( $this->local."test2.txt" );
		@unlink( $this->local."folder/test3.txt" );
		@unlink( $this->local."folder/test4.txt" );
		@rmDir( $this->local."folder/nested" );
		@rmDir( $this->local."folder" );
		@rmDir( $this->local );

		if( '' === $this->local )
			return;

		$this->connection	= new Connection( $host, $port );
		$this->connection->login( $username, $password );


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
	 *	@access		protected
	 *	@return		void
	 */
	protected function tearDown(): void
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
}

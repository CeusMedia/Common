<?php
/** @noinspection PhpIllegalPsrClassPathInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */
/** @noinspection PhpUnhandledExceptionInspection */
/** @noinspection PhpDocMissingThrowsInspection */

declare( strict_types = 1 );

/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\CommonTest\Net\FTP;

use CeusMedia\Common\Net\FTP\Connection;

/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ConnectionTest extends FtpTestCase
{
	protected ?Connection $connection	= NULL;
	protected ?string $host;
	protected ?int $port;
	protected ?string $username;
	protected ?string $password;
	protected ?string $local;
	protected ?string $path;
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
		$this->config	= self::getFtpConfig();
		$this->host		= $this->config['host'] ?? NULL;
		$this->port		= (int) ( $this->config['port'] ?? 0 );
		$this->username	= $this->config['user'] ?? NULL;
		$this->password	= $this->config['pass'] ?? NULL;
		$this->path		= $this->config['path'] ?? NULL;
		$this->local	= $this->config['local'] ?? NULL;
		if( [] !== $this->config ){
			@mkdir( (string) $this->local );
			$this->connection	= new Connection( $this->host, $this->port );
		}
		$this->checkFtpConfig();
	}

	/**
	 *	@param		bool	$markSkipped		Flag: Mark test as skipped, default: yes;
	 *	@return		bool
	 */
	protected function checkFtpConfig( bool $markSkipped = TRUE ): bool
	{
		if( NULL !== $this->connection )
			return TRUE;
		if( $markSkipped )
			$this->markTestSkipped( 'Test FTP server is not available' );
		return FALSE;
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		if( empty( $this->local ) )
			return;
		$this->connection?->close( TRUE );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$connection	= new Connection( $this->host, $this->port );
		self::assertInstanceOf( \FTP\Connection::class, $connection->getResource() );

		$assertion	= $this->host;
		$creation	= $connection->getHost();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__destruct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDestruct()
	{
		$this->connection->__destruct();
		self::assertNull( $this->connection->getResource() );
	}

	/**
	 *	Tests Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnection()
	{
		$connection	= new Connection( $this->host, $this->port );
		self::assertTrue( $connection->checkConnection( TRUE, FALSE ) );
		self::assertFalse( $connection->checkConnection( TRUE, TRUE, FALSE ) );
		$connection->login( $this->username, $this->password );
		self::assertTrue( $connection->checkConnection( TRUE, TRUE ) );
	}

	/**
	 *	Tests Exception of Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnectionException1()
	{
		$this->connection->close();
		$this->expectException( 'RuntimeException' );
		$this->connection->checkConnection( TRUE, FALSE );
	}

	/**
	 *	Tests Exception of Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnectionException2()
	{
		$this->expectException( 'RuntimeException' );
		$this->connection->checkConnection( TRUE, TRUE );
	}

	/**
	 *	Tests Method 'close'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClose()
	{
		$creation	= $this->connection->close();
		self::assertTrue( $creation );
	}

	/**
	 *	Tests Method 'connect'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnect()
	{
		$connection	= new Connection( $this->host, $this->port, 2 );
		self::assertInstanceOf( \FTP\Connection::class, $connection->getResource() );

		$assertion	= 2;
		$creation	= $connection->getTimeout();
		self::assertEquals( $assertion, $creation );

		$connection	= new Connection( "127.0.0.1", 1, 1 );
		self::assertNull( $connection->getResource() );
	}

	/**
	 *	Tests Method 'getHost'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetHost()
	{
		$this->checkFtpConfig();
		$assertion	= $this->host;
		$creation	= $this->connection->getHost();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPort'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPort()
	{
		$this->checkFtpConfig();
		$assertion	= $this->port;
		$creation	= $this->connection->getPort();
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
		$this->login();

		$assertion	= preg_replace( '/^(.+)\/$/', '\\1', "/".$this->path );
		$creation	= $this->connection->getPath();
		self::assertEquals( $assertion, $creation );

		@rmDir( $this->local."folder" );
		@mkDir( $this->local."folder" );
		$this->connection->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->connection->getPath();
		self::assertEquals( $assertion, $creation );

		@rmDir( $this->local."folder" );
	}

	/**
	 *	Tests Method 'getResource'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetResource()
	{
		$this->checkFtpConfig();
		self::assertInstanceOf( \FTP\Connection::class, $this->connection->getResource() );
		$this->connection->close();
		self::assertNull( $this->connection->getResource() );
	}

	/**
	 *	Tests Method 'getTimeout'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTimeout()
	{
		$this->checkFtpConfig();
		$assertion	= 90;
		$creation	= $this->connection->getTimeout();
		self::assertEquals( $assertion, $creation );

		$this->connection->setTimeout( 8 );

		$assertion	= 8;
		$creation	= $this->connection->getTimeout();
		self::assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'login'.
	 *	@access		public
	 *	@return		void
	 */
	public function testLogin()
	{
		if( !$this->checkFtpConfig() )
			return;
		$creation	= $this->connection->login( $this->username, $this->password );
		self::assertTrue( $creation );

		$creation	= $this->connection->login( "wrong_user", "wrong_pass" );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'setMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTransferMode()
	{
		$this->checkFtpConfig();
		$creation	= $this->connection->setTransferMode( FTP_ASCII );
		self::assertTrue( $creation );

		$creation	= $this->connection->setTransferMode( FTP_BINARY );
		self::assertTrue( $creation );

		$creation	= $this->connection->setTransferMode( -1 );
		self::assertFalse( $creation );
	}

	/**
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{
		$this->checkFtpConfig();
		@rmDir( $this->local."folder" );
		@mkDir( $this->local."folder" );

		$this->login();

		$creation	= $this->connection->setPath( "not_existing" );
		self::assertFalse( $creation );

		$creation	= $this->connection->setPath( "folder" );
		self::assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->connection->getPath();
		self::assertEquals( $assertion, $creation );

		@rmDir( $this->local."folder" );
	}

	/**
	 *	Tests Method 'setTimeout'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTimeout()
	{
		$this->checkFtpConfig();
		$creation	= $this->connection->setTimeout( 0 );
		self::assertFalse( $creation );

		$creation	= $this->connection->setTimeout( 9 );
		self::assertTrue( $creation );

		$assertion	= 9;
		$creation	= $this->connection->getTimeout();
		self::assertEquals( $assertion, $creation );
	}
}

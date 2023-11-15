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
use CeusMedia\CommonTest\BaseCase;

/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ConnectionTest extends BaseCase
{
	protected ?Connection $connection	= NULL;
	protected ?string $host;
	protected ?int $port;
	protected ?string $username;
	protected ?string $password;
	protected ?string $local;
	protected ?string $path;
	protected array $config;

	protected function login()
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
		$this->local	= $this->config['local'] ?? NULL;
		if( $this->checkFtpConfig() )
			$this->connection	= new Connection( $this->host, $this->port );
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
		if( empty( $this->local ) )
			return;
		$this->connection->close( TRUE );
		@rmDir( $this->local );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$connection	= new Connection( $this->host, $this->port );
		$this->assertIsResource( $connection->getResource() );

		$assertion	= $this->host;
		$creation	= $connection->getHost();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__destruct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDestruct()
	{
		$this->connection->__destruct();
		$this->assertNull( $this->connection->getResource() );
	}

	/**
	 *	Tests Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnection()
	{
		$connection	= new Connection( $this->host, $this->port );
		$creation	= $connection->checkConnection( TRUE, FALSE );
		$connection->login( $this->username, $this->password );
		$creation	= $connection->checkConnection( TRUE, TRUE );
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
		$this->assertTrue( $creation );
	}

	/**
	 *	Tests Method 'connect'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnect()
	{
		$connection	= new Connection( "127.0.0.1", 21, 2 );
		$this->assertIsResource( $connection->getResource() );

		$assertion	= 2;
		$creation	= $connection->getTimeout();
		$this->assertEquals( $assertion, $creation );

		$connection	= new Connection( "not_existing", 1, 1 );
		$this->assertIsNotResource( $connection->getResource() );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );
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
		$this->assertEquals( $assertion, $creation );

		@rmDir( $this->local."folder" );
		@mkDir( $this->local."folder" );
		$this->connection->setPath( "folder" );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->connection->getPath();
		$this->assertEquals( $assertion, $creation );

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
		$this->assertIsResource( $this->connection->getResource() );
		$this->connection->close();
		$this->assertNull( $this->connection->getResource() );
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
		$this->assertEquals( $assertion, $creation );

		$this->connection->setTimeout( 8 );

		$assertion	= 8;
		$creation	= $this->connection->getTimeout();
		$this->assertEquals( $assertion, $creation );
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
		$this->assertTrue( $creation );

		$creation	= $this->connection->login( "wrong_user", "wrong_pass" );
		$this->assertFalse( $creation );
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
		$this->assertTrue( $creation );

		$creation	= $this->connection->setTransferMode( FTP_BINARY );
		$this->assertTrue( $creation );

		$creation	= $this->connection->setTransferMode( -1 );
		$this->assertFalse( $creation );
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
		$this->assertFalse( $creation );

		$creation	= $this->connection->setPath( "folder" );
		$this->assertTrue( $creation );

		$assertion	= "/".$this->path."folder";
		$creation	= $this->connection->getPath();
		$this->assertEquals( $assertion, $creation );

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
		$this->assertFalse( $creation );

		$creation	= $this->connection->setTimeout( 9 );
		$this->assertTrue( $creation );

		$assertion	= 9;
		$creation	= $this->connection->getTimeout();
		$this->assertEquals( $assertion, $creation );
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
declare( strict_types = 1 );

namespace CeusMedia\Common\Test\Net\FTP;

use CeusMedia\Common\Net\FTP\Connection;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class ConnectionTest extends BaseCase
{
	protected $connection;
	protected $host;
	protected $port;
	protected $username;
	protected $password;
	protected $local;
	protected $path;
	protected $config;

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
		$this->config	= self::$_config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in cmClasses.ini' );

		@mkDir( $this->local );
		$this->connection	= new Connection( $this->host, $this->port );
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
		$creation	= $this->connection->setTimeout( 0 );
		$this->assertFalse( $creation );

		$creation	= $this->connection->setTimeout( 9 );
		$this->assertTrue( $creation );

		$assertion	= 9;
		$creation	= $this->connection->getTimeout();
		$this->assertEquals( $assertion, $creation );
	}
}

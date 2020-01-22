<?php
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
declare( strict_types = 1 );

use PHPUnit\Framework\TestCase;

/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@extends		Test_Case
 *	@uses			Net_FTP_Connection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Test_Net_FTP_ConnectionTest extends Test_Case
{
	protected $connection;

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
		$this->config	= self::$config['unitTest-FTP'];
		$this->host		= $this->config['host'];
		$this->port		= $this->config['port'];
		$this->username	= $this->config['user'];
		$this->password	= $this->config['pass'];
		$this->path		= $this->config['path'];
		$this->local	= $this->config['local'];

		if( !$this->local )
			$this->markTestSkipped( 'No FTP data set in cmClasses.ini' );

		@mkDir( $this->local );
		$this->connection	= new Net_FTP_Connection( $this->host, $this->port );
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
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
		$connection	= new Net_FTP_Connection( $this->host, $this->port );
		$assertion	= TRUE;
		$creation	= is_resource( $connection->getResource() );
		$this->assertEquals( $assertion, $creation );

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

		$assertion	= NULL;
		$creation	= $this->connection->getResource();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnection()
	{
		$connection	= new Net_FTP_Connection( $this->host, $this->port );
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
		$assertion	= TRUE;
		$creation	= $this->connection->close();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'connect'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnect()
	{
		$connection	= new Net_FTP_Connection( "127.0.0.1", 21, 2 );
		$assertion	= TRUE;
		$creation	= is_resource( $connection->getResource() );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $connection->getTimeout();
		$this->assertEquals( $assertion, $creation );

		$connection	= new Net_FTP_Connection( "not_existing", 1, 1 );
		$assertion	= FALSE;
		$creation	= is_resource( $connection->getResource() );
		$this->assertEquals( $assertion, $creation );
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
		$assertion	= TRUE;
		$creation	= is_resource( $this->connection->getResource() );
		$this->assertEquals( $assertion, $creation );

		$this->connection->close();

		$assertion	= NULL;
		$creation	= $this->connection->getResource();
		$this->assertEquals( $assertion, $creation );
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
		$assertion	= TRUE;
		$creation	= $this->connection->login( $this->username, $this->password );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->connection->login( "wrong_user", "wrong_pass" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTransferMode()
	{
		$assertion	= TRUE;
		$creation	= $this->connection->setTransferMode( FTP_ASCII );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->connection->setTransferMode( FTP_BINARY );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->connection->setTransferMode( -1 );
		$this->assertEquals( $assertion, $creation );
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

		$assertion	= FALSE;
		$creation	= $this->connection->setPath( "not_existing" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->connection->setPath( "folder" );
		$this->assertEquals( $assertion, $creation );

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
		$assertion	= FALSE;
		$creation	= $this->connection->setTimeout( 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->connection->setTimeout( 9 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 9;
		$creation	= $this->connection->getTimeout();
		$this->assertEquals( $assertion, $creation );
	}
}

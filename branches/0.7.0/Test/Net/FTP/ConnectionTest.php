<?php
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Test_Net_FTP_ConnectionTest extends PHPUnit_Framework_TestCase
{
	protected $connection;

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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$this->connection->close();
		$this->setExpectedException( 'RuntimeException' );
		$this->connection->checkConnection( TRUE, FALSE );
	}

	/**
	 *	Tests Exception of Method 'checkConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCheckConnectionException2()
	{
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		$this->setExpectedException( 'RuntimeException' );
		$this->connection->checkConnection( TRUE, TRUE );
	}

	/**
	 *	Tests Method 'close'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClose()
	{
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );

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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
		@rmDir( $this->local."folder" );
		@mkDir( $this->local."folder" );

		$this->login();

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
		if( empty( $this->local ) )
			$this->markTestIncomplete( 'No FTP data set in cmClasses.ini' );
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
?>
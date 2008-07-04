<?php
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.net.ftp.Connection' );
/**
 *	TestUnit of Net_FTP_Connection.
 *	@package		Tests.net.ftp
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Net_FTP_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			01.07.2008
 *	@version		0.1
 */
class Tests_Net_FTP_ConnectionTest extends PHPUnit_Framework_TestCase
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
		$this->connection	= new Net_FTP_Connection( $this->host, $this->port );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		$this->connection->close( TRUE );
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
		$connection	= new Net_FTP_Connection( "127.0.0.1", 21 );
		$assertion	= TRUE;
		$creation	= is_resource( $connection->getResource() );
		$this->assertEquals( $assertion, $creation );

		$this->markTestIncomplete( 'Incomplete Test' );
		$connection	= new Net_FTP_Connection( "not_existing", 1 );
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
		$this->connection->login( $this->username, $this->password );

		$assertion	= "/";
		$creation	= $this->connection->getPath();
		$this->assertEquals( $assertion, $creation );

		@rmDir( $this->ftpPath."folder" );
		@mkDir( $this->ftpPath."folder" );
		$this->connection->setPath( "folder" );		

		$assertion	= "/folder";
		$creation	= $this->connection->getPath();
		$this->assertEquals( $assertion, $creation );

		@rmDir( $this->ftpPath."folder" );
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
	 *	Tests Method 'setPath'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPath()
	{

		@rmDir( $this->ftpPath."folder" );
		@mkDir( $this->ftpPath."folder" );

		$this->connection->login( $this->username, $this->password );

		$assertion	= TRUE;
		$creation	= $this->connection->setPath( "folder" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "/folder";
		$creation	= $this->connection->getPath();
		$this->assertEquals( $assertion, $creation );

		@rmDir( $this->ftpPath."folder" );
	}

	/**
	 *	Tests Method 'setMode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetMode()
	{
		$assertion	= TRUE;
		$creation	= $this->connection->setMode( FTP_ASCII );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= $this->connection->setMode( FTP_BINARY );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->connection->setMode( -1 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
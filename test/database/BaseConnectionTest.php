<?php
/**
 *	TestUnit of Database_BaseConnection.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_BaseConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.database.BaseConnection' );
/**
 *	TestUnit of Database_BaseConnection.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_BaseConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Database_BaseConnectionTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->connection	= new Database_BaseConnectionInstance();
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct()
	{
		$connection	= new Database_BaseConnectionInstance( "test" );

		$assertion	= "test";
		$creation	= $connection->getProtectedVar( 'logFile' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $connection->getProtectedVar( 'connected' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'connect'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testConnect()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_BaseConnection::connect();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isConnected'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsConnected()
	{
		$assertion	= FALSE;
		$creation	= $this->connection->isConnected();
		$this->assertEquals( $assertion, $creation );

		$this->connection->setProtectedVar( 'connected', TRUE );
		$assertion	= TRUE;
		$creation	= $this->connection->isConnected();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLogFile()
	{
		$this->connection->setLogFile( "test" );
		$assertion	= "test";
		$creation	= $this->connection->getProtectedVar( 'logFile' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setErrorReporting'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetErrorReporting()
	{
		$this->connection->setErrorReporting( 0 );
		$assertion	= 0;
		$creation	= $this->connection->getProtectedVar( 'errorLevel' );
		$this->assertEquals( $assertion, $creation );

		$this->connection->setErrorReporting( 1 );
		$assertion	= 1;
		$creation	= $this->connection->getProtectedVar( 'errorLevel' );
		$this->assertEquals( $assertion, $creation );

		$this->connection->setErrorReporting( 2 );
		$assertion	= 2;
		$creation	= $this->connection->getProtectedVar( 'errorLevel' );
		$this->assertEquals( $assertion, $creation );
	}
}
class Database_BaseConnectionInstance extends Database_BaseConnection
{
	function getProtectedVar( $key )
	{
		return $this->$key;
	}
	function setProtectedVar( $key, $value )
	{
		$this->$key	= $value;
	}
	function beginTransaction(){}
	function close(){}
	function commit(){}
	function execute( $query, $debug = 1 ){}
	function getErrNo(){}
	function getError(){}
	function getInsertId(){}
	function getTables(){}
	function rollback(){}
	function selectDB( $database ){}
}
?>
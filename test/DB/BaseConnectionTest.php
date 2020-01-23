<?php
/**
 *	TestUnit of DB_BaseConnection.
 *	@package		Tests.database
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once dirname( __DIR__ ).'/initLoaders.php';
/**
 *	TestUnit of DB_BaseConnection.
 *	@package		Tests.database
 *	@extends		Test_Case
 *	@uses			DB_BaseConnection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Test_DB_BaseConnectionTest/* extends Test_Case*/
{
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->connection	= new Test_DB_BaseConnectionInstance();
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
	public function _testConstruct()
	{
		$connection	= new Test_DB_BaseConnectionInstance( "test" );

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
		$creation	= DB_BaseConnection::connect();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isConnected'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testIsConnected()
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
	public function _testSetLogFile()
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
	public function _testSetErrorReporting()
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
class Test_DB_BaseConnectionInstance extends DB_BaseConnection
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

<?php
/**
 *	TestUnit of DB_PDO_Connection.
 *	@package		Tests.database.pdo
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of DB_PDO_Connection.
 *	@package		Tests.database.pdo
 *	@extends		Test_Case
 *	@uses			DB_PDO_Connection
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
class Test_DB_PDO_ConnectionTest extends Test_Case{

	protected $directDbc;

	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp(): void
	{
		if( !extension_loaded( 'pdo_mysql' ) )
			$this->markTestSkipped( "PDO driver for MySQL not supported" );

		$config			= self::$_config['unitTest-Database'];
		$this->host		= $config['host'];
		$this->port		= $config['port'];
		$this->username	= $config['username'];
		$this->password	= $config['password'];
		$this->database	= $config['database'];
		$this->path		= dirname( __FILE__ )."/";
		$this->errorLog	= $this->path."errors.log";
		$this->queryLog	= $this->path."queries.log";
		$this->options	= array();

//		$this->connect();
	}

	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown(): void
	{
		@unlink( $this->errorLog );
		@unlink( $this->queryLog );
		if( extension_loaded( 'mysql' ) ){
			mysql_query( "DROP TABLE transactions" );
			mysql_close( $this->directDbc );
		}
		else if( extension_loaded( 'mysqli' ) && $this->directDbc ){
			mysqli_query( $this->directDbc, "DROP TABLE transactions" );
			mysqli_close( $this->directDbc );
		}
	}

	private function connect(){
		$dsn 		= "mysql:host=".$this->host.";dbname=".$this->database;
		$this->connection	= new DB_PDO_Connection( $dsn, $this->username, $this->password, $this->options );
		$this->connection->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );
		$this->connection->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );
		$this->connection->setErrorLogFile( $this->errorLog );
		$this->connection->setStatementLogFile( $this->queryLog );
		if( extension_loaded( 'mysql' ) ){
			$this->directDbc	= mysql_connect( $this->host, $this->username, $this->password ) or die( mysql_error() );
			mysql_select_db( $this->database );
			$sql	= file_get_contents( $this->path."createTable.sql" );
			foreach( explode( ";", $sql ) as $part )
				if( trim( $part ) )
					mysql_query( $part ) or die( mysql_error() );
		}
		else if( extension_loaded( 'mysqli' ) ){
			$this->directDbc	= new mysqli( $this->host, $this->username, $this->password ) or die( mysqli_error() );
			mysqli_select_db( $this->directDbc, $this->database );
			$sql	= file_get_contents( $this->path."createTable.sql" );
			foreach( explode( ";", $sql ) as $part )
				if( trim( $part ) )
					mysqli_query( $this->directDbc, $part ) or die( mysqli_error() );
		}
		else{
			throw new RuntimeException( 'No suitable MySQL connector found' );
		}
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct(){
		$this->expectDeprecation();
		$this->connect();
	}

	/**
	 *	Tests Method 'beginTransaction'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testBeginTransaction(){
		$assertion	= TRUE;
		$creation	= $this->connection->beginTransaction();
		$this->assertEquals( $assertion, $creation );

		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('begin','beginTransactionTest');" );
		$this->connection->rollBack();

		$result		= $this->connection->query( "SELECT * FROM transactions" );

		$assertion	= 1;
		$creation	= $result->rowCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'commit'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testCommit(){
		$this->connection->beginTransaction();

		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('begin','beginTransactionTest');" );
		$assertion	= TRUE;
		$creation	= $this->connection->commit();
		$this->assertEquals( $assertion, $creation );

		$result		= $this->connection->query( "SELECT * FROM transactions" );

		$assertion	= 2;
		$creation	= $result->rowCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'exec'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testExec(){
		for( $i=0; $i<10; $i++ )
			$this->connection->query( "INSERT INTO transactions (topic, label) VALUES ('test', '".microtime()."');" );

		$assertion	= 11;
		$creation	= $this->connection->exec( "UPDATE transactions SET topic='exec' WHERE topic!='exec'" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->connection->exec( "UPDATE transactions SET topic='exec' WHERE topic!='exec'" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 11;
		$creation	= $this->connection->exec( "DELETE FROM transactions WHERE topic='exec'" );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->connection->exec( "DELETE FROM transactions WHERE topic='exec'" );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'prepare'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testPrepare(){
		$statement	= $this->connection->prepare( "SELECT * FROM transactions" );

		$assertion	= TRUE;
		$creation	= is_object( $statement );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= is_a( $statement, 'PDOStatement' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= TRUE;
		$creation	= file_exists( $this->queryLog );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->connection->numberStatements;
		$this->assertEquals( $assertion, $creation );

		$statement	= $this->connection->prepare( "SELECT * FROM transactions" );

		$assertion	= 2;
		$creation	= $this->connection->numberStatements;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'query'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testQuery(){
		$assertion	= FALSE;
		$creation	= NULL;
		try
		{
			$creation	= $this->connection->query( "SELECT none FROM nowhere" );
		}
		catch( Exception $e ){}
		$this->assertEquals( $assertion, $creation );

		$result		= $this->connection->query( "SELECT * FROM transactions" );

		$assertion	= TRUE;
		$creation	= is_object( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result->rowCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= $result->columnCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->connection->numberStatements;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rollBack'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testRollBack(){
		$this->connection->beginTransaction();
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('begin','beginTransactionTest');" );

		$assertion	= TRUE;
		$creation	= $this->connection->rollBack();
		$this->assertEquals( $assertion, $creation );


		$result		= $this->connection->query( "SELECT * FROM transactions" );

		$assertion	= 1;
		$creation	= $result->rowCount();
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'setErrorLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testSetErrorLogFile(){
		$logFile	= $this->path."error_log";
		$this->connection->setErrorLogFile( $logFile );
		try{
			$this->connection->query( "SELECT none FROM nowhere" );
		}catch( Exception_SQL $e ){}

		$assertion	= TRUE;
		$creation	= file_exists( $logFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $logFile );
	}

	/**
	 *	Tests Method 'setStatementLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testSetStatementLogFile(){
		$logFile	= $this->path."statement_log";
		$this->connection->setStatementLogFile( $logFile );
		try{
			$this->connection->query( "SELECT none FROM nowhere" );
		}catch( Exception_SQL $e ){}

		$assertion	= TRUE;
		$creation	= file_exists( $logFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $logFile );
	}
}

<?php
/**
 *	TestUnit of Database_PDO_Connection.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.pdo.Connection' );
/**
 *	TestUnit of Database_PDO_Connection.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
class Tests_Database_PDO_ConnectionTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->host		= "localhost";
		$this->port		= 3306;
		$this->username	= "ceus";
		$this->password	= "ceus";
		$this->database	= "test";
		$this->path		= dirname( __FILE__ )."/";
		$this->errorLog	= $this->path."errors.log";
		$this->queryLog	= $this->path."queries.log";
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$dsn = "mysql:host=".$this->host.";dbname=".$this->database;
		$this->connection	= new Database_PDO_Connection( $dsn, $this->username, $this->password, $options );
		$this->connection->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );
		$this->connection->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );
		$this->connection->setErrorLogFile( $this->errorLog );
		$this->connection->setStatementLogFile( $this->queryLog );
		$this->mysql	= mysql_connect( $this->host, $this->username, $this->password ) or die( mysql_error() );
		mysql_select_db( $this->database );
		$sql	= file_get_contents( $this->path."createTable.sql" );
		foreach( explode( ";", $sql ) as $part )
			if( trim( $part ) )
				mysql_query( $part ) or die( mysql_error() );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->errorLog );
		@unlink( $this->queryLog );
		mysql_query( "DROP TABLE transactions" );
	}

	/**
	 *	Tests Method 'beginTransaction'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBeginTransaction()
	{
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
	public function testCommit()
	{
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
	public function testExec()
	{
		for( $i=0; $i<10; $i++ )
			$this->connection->query( "INSERT INTO transactions (label) VALUES ('".microtime()."');" );

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
	public function testPrepare()
	{
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
	public function testQuery()
	{
		$assertion	= FALSE;
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
	public function testRollBack()
	{
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
	public function testSetErrorLogFile()
	{
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
	 *	Tests Method 'setLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLogFile()
	{
		$logFile	= $this->path."error_log";
		$this->connection->setLogFile( $logFile );
		try{
			$this->connection->query( "SELECT none FROM nowhere" );
		}catch( Exception_SQL $e ){}

		$assertion	= TRUE;
		$creation	= file_exists( $logFile );
		$this->assertEquals( $assertion, $creation );
		@unlink( $logFile );
	}

	/**
	 *	Tests Method 'setQueryLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetQueryLogFile()
	{
		$logFile	= $this->path."statement_log";
		$this->connection->setQueryLogFile( $logFile );
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
	public function testSetStatementLogFile()
	{
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
?>
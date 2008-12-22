<?php
/**
 *	TestUnit of Database_PDO_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.pdo.Connection' );
import( 'de.ceus-media.database.pdo.TableWriter' );
/**
 *	TestUnit of Database_PDO_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Database_PDO_TableWriterTest extends PHPUnit_Framework_TestCase
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
		$options		= array();

		$this->dsn = "mysql:host=".$this->host.";dbname=".$this->database;

		$this->connection	= new Database_PDO_Connection( $this->dsn, $this->username, $this->password, $options );
		$this->connection->setAttribute( PDO::ATTR_CASE, PDO::CASE_NATURAL );
		$this->connection->setAttribute( PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, TRUE );
		$this->connection->setErrorLogFile( $this->errorLog );
		$this->connection->setStatementLogFile( $this->queryLog );

		$this->tableName	= "transactions";
		$this->columns	= array(
			'id',
			'topic',
			'label',
			'timestamp',
		);
		$this->primaryKey	= $this->columns[0];
		$this->indices	= array(
			'topic',
			'label'
		);
	}
	
	/**
	 *	Setup for every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function setUp()
	{
		$this->mysql	= mysql_connect( $this->host, $this->username, $this->password ) or die( mysql_error() );
		mysql_select_db( $this->database );
		$sql	= file_get_contents( $this->path."createTable.sql" );
		foreach( explode( ";", $sql ) as $part )
			if( trim( $part ) )
				mysql_query( $part ) or die( mysql_error() );

		$this->writer	= new Database_PDO_TableWriter( $this->connection, $this->tableName, $this->columns, $this->primaryKey );
		$this->writer->setIndices( $this->indices );
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
	 *	Tests Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDelete()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );

		$assertion	= 4;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );
		
		$this->writer->focusPrimary( 4 );
		$assertion	= 1;
		$creation	= $this->writer->delete();
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 3;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $this->writer->find( array(), array( 'label' => 'deleteTest' ) ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusIndex( 'label', 'deleteTest' );
		$assertion	= 2;
		$creation	= $this->writer->delete();
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 1;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$this->writer->focusPrimary( 999999 );
		$assertion	= 0;
		$creation	= $this->writer->delete();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteException1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->writer->delete();
	}

	/**
	 *	Tests Method 'deleteByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteByConditions()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );

		$assertion	= 4;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->writer->deleteByConditions( array( 'label' => 'deleteTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insert'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsert()
	{
		$data	= array(
			'topic'	=> 'insert',
			'label'	=> 'insertTest',
		);

		$assertion	= 2;
		$creation	= $this->writer->insert( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusPrimary( 2 );
		$assertion	= $data;
		$creation	= array_slice( $this->writer->get( TRUE ), 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusIndex( 'topic', 'insert' ); 
		$assertion	= 3;
		$creation	= $this->writer->insert( array( 'label' => 'insertTest2' ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 3;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );
		
		$results	= $this->writer->find( array( 'label' ) );
		$assertion	= array( 'label' => 'insertTest2' );
		$creation	= array_pop( $results );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdatePrimary()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->writer->focusPrimary( 2 );

		$data		= array(
			'label'	=> "updateTest1-changed"
		);
		$assertion	= 1;
		$creation	= $this->writer->update( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'label' => "updateTest1-changed" );
		$creation	= array_pop( $this->writer->find( array( 'label' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateIndex()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->writer->focusIndex( 'topic', 'update' );

		$data		= array(
			'label'	=> "changed"
		);
		$assertion	= 2;
		$creation	= $this->writer->update( $data );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusIndex( 'label', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writer->get( FALSE ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->updateByConditions( array() );
	}

	/**
	 *	Tests Exception of Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->focusPrimary( 9999 );
		$this->writer->update( array( 'label' => 'not_relevant' ));
	}

	/**
	 *	Tests Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateByConditions()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );

		$conditions	= array(
			'label' => "updateTest1"
		);
		$data		= array(
			'label'	=> "updateTest1-changed"
		);

		$assertion	= 1;
		$creation	= $this->writer->updateByConditions( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'label' => "updateTest1-changed" );
		$creation	= array_pop( $this->writer->find( array( 'label' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );

		$conditions	= array(
			'topic' => "update"
		);
		$data		= array(
			'label'	=> "changed"
		);

		$assertion	= 2;
		$creation	= $this->writer->updateByConditions( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusIndex( 'label', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writer->get( FALSE ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateByConditionsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->updateByConditions( array(), array( 'label' => 'not_relevant' ) );
	}

	/**
	 *	Tests Exception of Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateByConditionsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->updateByConditions( array( 'label' => 'not_relevant' ), array() );
	}

	/**
	 *	Tests Method 'truncate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTruncate()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('truncateTest');" );

		$assertion	= 2;
		$creation	= $this->writer->truncate();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
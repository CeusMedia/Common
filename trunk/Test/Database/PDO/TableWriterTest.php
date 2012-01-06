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
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
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
class Test_Database_PDO_TableWriterTest extends PHPUnit_Framework_TestCase
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		global $__config;
		$this->host		= $__config['unitTest-Database']['host'];
		$this->port		= $__config['unitTest-Database']['port'];
		$this->username	= $__config['unitTest-Database']['username'];
		$this->password	= $__config['unitTest-Database']['password'];
		$this->database	= $__config['unitTest-Database']['database'];
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
		$this->tableName2	= "transactions2";
		$this->columns2	= array(
			'id2',
			'topic2',
			'label2',
			'timestamp2',
		);
		$this->primaryKey2	= $this->columns2[0];
		$this->indices2	= array(
			'topic2',
			'label2'
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
		$this->writer2	= new Database_PDO_TableWriter( $this->connection, $this->tableName2, $this->columns2, $this->primaryKey2 );
		$this->writer2->setIndices( $this->indices2 );
		$this->writerJoin = $this->writer->Join($this->writer2,array('id','id2'));
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
		//mysql_query( "DROP TABLE transactions" );
		//mysql_query( "DROP TABLE transactions2" );
	}

	/**
	 *	Tests Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testDelete()
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
	 * 
	 * test delete with join
	 */
	public function _testDeleteJoin(){
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest');" );
		
		$assertion	= 4;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->focusPrimary( 4 );
		$assertion	= 2;
		$creation	= $this->writerJoin->delete();
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->defocus();
		$assertion	= 3;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 2;
		$creation	= count( $this->writerJoin->find( array(), array( 'label' => 'deleteTest' ) ) );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 2;
		$creation	= count( $this->writerJoin->find( array(), array( 'transactions2.label2' => 'deleteTest' ) ) );
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->focusIndex( 'label2', 'deleteTest' );
		$assertion	= 4;
		$creation	= $this->writerJoin->delete();
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->defocus();
		$assertion	= 1;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->defocus();
		$this->writerJoin->focusPrimary( 999999 );
		$assertion	= 0;
		$creation	= $this->writerJoin->delete();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception of Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testDeleteException1()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->writer->delete();
	}
	
	/**
	 * 
	 * Tests Exception of Method 'delete' whit join.
	 */
	public function _testDeleteJoinException1(){
		$this->setExpectedException( 'RuntimeException' );
		$this->writerJoin->delete();
	}
	
	/**
	 *	Tests Method 'deleteByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testDeletenByConditions()
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
	 *	Tests Method 'deleteByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testDeleteJoinByConditions()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		
		$assertion	= 4;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 6;
		$creation	= $this->writerJoin->deleteByConditions( array( 'label' => 'deleteTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
	}	
	
	
	/**
	 *	Tests Method 'deleteByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testDeleteJoinByConditions2()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('deleteTest2');" );
		
		$assertion	= 4;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->writerJoin->deleteByConditions( array( 'label2' => 'deleteTest' ) );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 6;
		$creation	= $this->writerJoin->deleteByConditions( array( 'label' => 'deleteTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'insert'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testInsert()
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

	public function _testInsertWithAlias(){
		$this->writer->setAlias('sa');
		$data	= array(
			'topic'	=> 'insert',
			'label'	=> 'insertTest',
		);
		$assertion	= 2;
		$creation	= $this->writer->insert( $data );
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
	
	public function _testInsetWithJoin(){
		$data	= array(
			'topic'	=> 'insert',
			'label'	=> 'insertTest',
			'label2'	=> 'insertTest2',
			'topic2'	=> 'insert2',
		);
		
		$this->writer->insert( $data );
		
		$assertion	= 3;
		$creation	= $this->writerJoin->insert( $data );
		$this->assertEquals( $assertion, $creation );
		
	    $this->writerJoin->focusPrimary( 3 );
	    $data['id2'] = 3;
		$assertion	= $data;
		$creation	= array_merge(array_slice( $this->writerJoin->get( TRUE ), 1, 2),array_slice( $this->writerJoin->get( TRUE ), 4, 3));
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->focusIndex( 'topic', 'insert' );
		$this->writerJoin->focusIndex( 'topic2', 'insert2-1' ); 
		$assertion	= 4;
		$creation	= $this->writerJoin->insert( array( 'label' => 'insertTest2' ) );
		$this->assertEquals( $assertion, $creation );
		
		$this->writerJoin->defocus();
		$assertion	= 3;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
		
		$results	= $this->writerJoin->find( array( 'label','topic2' ) );
		$assertion	= array( 'label' 	=> 'insertTest2',
							 'topic2' 	=> 'insert2-1' );
		$creation	= array_pop( $results );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdatePrimary()
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
	 *	Tests Method 'update' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdatePrimaryWithJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest2');" );
		
		$this->writerJoin->focusPrimary( 2 );

		$data		= array(
			'label'	=> "updateTest1-changed"
		);
		$assertion	= 1;
		$creation	= $this->writerJoin->update( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'label' => "updateTest1-changed" );
		$creation	= array_pop( $this->writerJoin->find( array( 'label' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );
		
		$data		= array(
			'label2'	=> "updateTest1-changed"
		);		
		$assertion	= 1;
		$creation	= $this->writerJoin->update( $data );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= array( 'label2' => "updateTest1-changed" );
		$creation	= array_pop( $this->writerJoin->find( array( 'label2' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdateIndex()
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
	 *	Tests Method 'update' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdateIndexJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest2');" );
		$this->writerJoin->focusIndex( 'topic', 'update' );

		$data		= array(
			'label'	=> "changed",
			'label2'	=> "changed"
		);
		$assertion	= 4;
		$creation	= $this->writerJoin->update( $data );
		$this->assertEquals( $assertion, $creation );

		$this->writerJoin->focusIndex( 'label', 'changed' );
		$this->writerJoin->focusIndex( 'label2', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writerJoin->get( FALSE ) );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Exception of Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdateException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->updateByConditions( array() );
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writerJoin->updateByConditions( array() );
	}

	/**
	 *	Tests Exception of Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdateException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->focusPrimary( 9999 );
		$this->writer->update( array( 'label' => 'not_relevant' ));
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writerJoin->focusPrimary( 9999 );
		$this->writerJoin->update( array( 'label' => 'not_relevant' ));
	}

	/**
	 *	Tests Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function _testUpdateByConditions()
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
	 *	Tests Method 'updateByConditions' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateByConditionsJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest1');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('update','updateTest2');" );		

		$conditions	= array(
			'label' => "updateTest1",
			'label2' => "updateTest1"
		);
		$data		= array(
			'label'	=> "updateTest1-changed",
			'label2'	=> "updateTest1-changed",
		);

		$assertion	= 2;
		$creation	= $this->writerJoin->updateByConditions( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'label' => "updateTest1-changed",
							 'label2' => "updateTest1-changed"		
							 );
		$creation	= array_pop( $this->writerJoin->find( array( 'label','label2' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );

		$conditions	= array(
			'topic' => "update",
			'topic2' => "update"
		);
		$data		= array(
			'label'	=> "changed",
			'label2'	=> "changed"
		);

		$assertion	= 4;
		$creation	= $this->writerJoin->updateByConditions( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$this->writerJoin->focusIndex( 'label', 'changed' );
		$this->writerJoin->focusIndex( 'label2', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writerJoin->get( FALSE ) );
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
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writerJoin->updateByConditions( array(), array( 'label2' => 'not_relevant' ) );
		
	}

	/**
	 *	Tests Exception of Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function __testUpdateByConditionsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writer->updateByConditions( array( 'label' => 'not_relevant' ), array() );
		
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->writerJoin->updateByConditions( array( 'label2' => 'not_relevant' ), array() );
	}

	/**
	 *	Tests Method 'truncate'.
	 *	@access		public
	 *	@return		void
	 */
	public function __testTruncate()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('truncateTest');" );

		$assertion	= 2;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->writer->truncate();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0;
		$creation	= $this->writer->count();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'truncate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTruncateJoin()
	{
		$this->connection->query( "INSERT INTO transactions (label) VALUES ('truncateTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('truncateTest');" );

		$assertion	= 2;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->writerJoin->truncate();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0;
		$creation	= $this->writerJoin->count();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 0;
		$creation	= $this->writer2->count();
		$this->assertEquals( $assertion, $creation );
	}
}
?>

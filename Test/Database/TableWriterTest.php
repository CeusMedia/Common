<?php
/**
 *	TestUnit of Database_TableWriter.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_MySQL_Connection
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Database_TableWriter.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_MySQL_Connection
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Test_Database_TableWriterTest extends PHPUnit_Framework_TestCase
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
		$this->logFile	= $this->path."errors.log";
		$options		= array();

		$this->connection	= new Database_MySQL_Connection( $this->logFile );
		$this->connection->connect( $this->host, $this->username, $this->password, $options );

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

		$this->writer	= new Database_TableWriter( $this->connection, $this->tableName, $this->columns, $this->primaryKey );
		$this->writer->setForeignKeys( $this->indices );
	}
	
	/**
	 *	Cleanup after every Test.
	 *	@access		public
	 *	@return		void
	 */
	public function tearDown()
	{
		@unlink( $this->logFile );
		mysql_query( "DROP TABLE transactions" );
	}

	/**
	 *	Tests Method 'addData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddData()
	{
		$data	= array(
			'topic'	=> 'add',
			'label'	=> 'addTest',
		);

		$assertion	= 2;
		$creation	= $this->writer->addData( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusPrimary( 2 );
		$assertion	= $data;
		$creation	= array_slice( $this->writer->getData( TRUE ), 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'topic', 'add' ); 
		$assertion	= 3;
		$creation	= $this->writer->addData( array( 'label' => 'addTest2' ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 3;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );
		
		$results	= $this->writer->getAllData( array( 'label' ) );
		$assertion	= array( 'label' => 'addTest2' );
		$creation	= array_pop( $results );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteData()
	{
		$this->connection->execute( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->execute( "INSERT INTO transactions (label) VALUES ('deleteTest');" );
		$this->connection->execute( "INSERT INTO transactions (label) VALUES ('deleteTest');" );

		$assertion	= 4;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );
		
		$this->writer->focusPrimary( 4 );
		$assertion	= TRUE;
		$creation	= $this->writer->deleteData();
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 3;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $this->writer->getAllData( array(), array( 'label' => 'deleteTest' ) ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'label', 'deleteTest' );
		$assertion	= TRUE;
		$creation	= $this->writer->deleteData();
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 1;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->writer->deleteData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteDataWhere'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteDataWhere()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('delete','deleteTest1');" );
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('delete','deleteTest2');" );

		$conditions	= array(
			'topic'	=> 'not_existing'
		);
		$assertion	= TRUE;
		$creation	= $this->writer->deleteDataWhere( $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$conditions	= array(
			'not_existing'	=> 'not_relevant'
		);
		$assertion	= FALSE;
		$creation	= $this->writer->deleteDataWhere( $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 3;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$conditions	= array(
			'topic'	=> 'delete'
		);
		$assertion	= TRUE;
		$creation	= $this->writer->deleteDataWhere( $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->writer->deleteDataWhere();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insertData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsertData()
	{
		$data	= array(
			'topic'	=> 'add',
			'label'	=> 'addTest',
		);

		$assertion	= 2;
		$creation	= $this->writer->insertData( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusPrimary( 2 );
		$assertion	= $data;
		$creation	= array_slice( $this->writer->getData( TRUE ), 1, 2 );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'topic', 'add' ); 
		$assertion	= 3;
		$creation	= $this->writer->insertData( array( 'label' => 'addTest2' ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->defocus();
		$assertion	= 3;
		$creation	= $this->writer->getAllCount();
		$this->assertEquals( $assertion, $creation );
		
		$results	= $this->writer->getAllData( array( 'label' ) );
		$assertion	= array( 'label' => 'addTest2' );
		$creation	= array_pop( $results );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'modifyData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testModifyDataPrimary()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->writer->focusPrimary( 2 );

		$data		= array(
			'label'	=> "updateTest1-changed"
		);
		$assertion	= TRUE;
		$creation	= $this->writer->modifyData( $data );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'label' => "updateTest1-changed" );
		$creation	= array_pop( $this->writer->getAllData( array( 'label' ), array( 'id' => 2 ) ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusPrimary( 9999 );
		$assertion	= 4;
		$creation	= $this->writer->modifyData( $data );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'modifyData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testModifyDataForeign()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );
		$this->writer->focusForeign( 'topic', 'update' );

		$data		= array(
			'label'	=> "changed"
		);
		$assertion	= TRUE;
		$creation	= $this->writer->modifyData( $data );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'label', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writer->getData( FALSE ) );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'topic', 'not_existing' );
		$assertion	= 4;
		$creation	= $this->writer->modifyData( $data );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'modifyDataWhere'.
	 *	@access		public
	 *	@return		void
	 */
	public function testModifyDataWhere()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest1');" );
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('update','updateTest2');" );

		$data		= array(
			'label'	=> "changed"
		);
		$conditions	= array(
			'topic' => 'update'
		);
		$assertion	= TRUE;
		$creation	= $this->writer->modifyDataWhere( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'label', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writer->getData( FALSE ) );
		$this->assertEquals( $assertion, $creation );


		$data		= array(
			'label'	=> "not_relevant"
		);
		$conditions	= array(
			'label' => 'not_existing'
		);
		$assertion	= TRUE;
		$creation	= $this->writer->modifyDataWhere( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $this->writer->getData( FALSE ) );
		$this->assertEquals( $assertion, $creation );


		$conditions	= array(
			'not_existing' => 'not_relevant'
		);
		$assertion	= FALSE;
		$creation	= $this->writer->modifyDataWhere( $data, $conditions );
		$this->assertEquals( $assertion, $creation );

		$this->writer->focusForeign( 'label', 'changed' );
		$assertion	= 2;
		$creation	= count( $this->writer->getData( FALSE ) );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
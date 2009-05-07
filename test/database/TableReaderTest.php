<?php
/**
 *	TestUnit of Database_TableReader.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_MySQL_Connection
 *	@uses			Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.database.mysql.Connection' );
import( 'de.ceus-media.database.TableReader' );
/**
 *	TestUnit of Database_TableReader.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_MySQL_Connection
 *	@uses			Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Database_TableReaderTest extends PHPUnit_Framework_TestCase
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

		$this->connection	= new Database_MySQL_Connection( $this->logFile );
		$this->connection->connect( $this->host, $this->username, $this->password, $this->database );
		
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

		$this->reader	= new Database_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey );
		$this->reader->setForeignKeys( $this->indices );
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct1()
	{
		$reader		= new Database_TableReader( $this->connection, "table", array( 'col1', 'col2' ), 'col2', 1 );

		$assertion	= 'table';
		$creation	= $reader->getTableName();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'col1', 'col2' );
		$creation	= $reader->getFields();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'col2';
		$creation	= $reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct2()
	{
		$reader		= new Database_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 );
	
		$assertion	= array( 'id' => 1 );
		$creation	= array_slice( $reader->getData( TRUE ), 0, 1 );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'defocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocus()
	{
		$this->reader->focusPrimary( 2 );
		
		$assertion	= 2;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();

		$assertion	= FALSE;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );


		$this->reader->focusForeign( 'topic', 'test' );
		
		$assertion	= array( 'topic' => 'test' );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();

		$assertion	= FALSE;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusForeign'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusForeign()
	{
		$assertion	= TRUE;
		$creation	= $this->reader->focusForeign( 'topic', 'test' );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			'topic' => 'test'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusForeign( 'topic', 'something_else' );
		$assertion	= array(
			'topic' => 'something_else'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusForeign( 'label', 'text' );
		$assertion	= array(
			'topic' => 'something_else',
			'label'	=> 'text'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->reader->focusForeign( 'not_existing', 'not_relevant' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusPrimary'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusPrimary()
	{
		$this->reader->focusPrimary( 2 );
		$assertion	= 2;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1 );
		$assertion	= 1;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllCount()
	{
		$assertion	= 1;
		$creation	= $this->reader->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$this->connection->execute( "INSERT INTO transactions (label) VALUES ('countTest');" );

		$assertion	= 2;
		$creation	= $this->reader->getAllCount();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->reader->getAllCount( array( 'label' => 'countTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader->getAllCount( array( 'label' => 'not_existing' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData1()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->getAllData();

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData2()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->getAllData( array( "*" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData3()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->getAllData( array( "id" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData4()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$conditions	= array( 'topic' => 'start' );
		$result		= $this->reader->getAllData( array( 'id' ), $conditions );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$conditions	= array( 'topic' => 'test' );
		$result		= $this->reader->getAllData( array( 'id' ), $conditions );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData5()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$conditions	= array( 'id' => 1 );
		$result		= $this->reader->getAllData( array( 'id' ), $conditions );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData6()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest1');" );
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest2');" );

		$conditions	= array( 'id' => "<2" );
		$result		= $this->reader->getAllData( array( 'id' ), $conditions );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$conditions	= array( 'label' => "%est%" );
		$result		= $this->reader->getAllData( array( 'id', 'label' ), $conditions );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "findTest1";
		$creation	= $result[0]['label'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= "findTest2";
		$creation	= $result[1]['label'];
		$this->assertEquals( $assertion, $creation );

		$conditions	= array( 'label' => ">findTest1" );
		$result		= $this->reader->getAllData( array( 'id', 'label' ), $conditions );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "findTest2";
		$creation	= $result[0]['label'];
		$this->assertEquals( $assertion, $creation );


		$conditions	= array( 'label' => "test=test" );
		$result		= $this->reader->getAllData( array( 'id', 'label' ), $conditions );

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllDataWithOrder()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->getAllData( array( 'id' ), array(), array( 'id' => 'ASC' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(
			array( 'id' => 1 ),
			array( 'id' => 2 ),
		);
		$creation	= $result;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllDataWithLimit()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->getAllData( array( 'id' ), array(), array( 'id' => 'DESC' ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( array( 'id' => 2 ) );
		$creation	= $result;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDataWithPrimary()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->reader->focusPrimary( 1 );
		$result		= $this->reader->getData();
				
		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0]['id'] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$result		= $this->reader->getData( TRUE );
		
		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDataWithForeign()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('start','getWithIndexTest');" );
		$this->reader->focusForeign( 'topic', 'start' );
		$result		= $this->reader->getData( TRUE );

		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->getData();

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusForeign( 'label', 'getWithIndexTest' );
		$result		= $this->reader->getData( FALSE );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDataWithOrder()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('start','getWithOrderTest');" );
		$this->reader->focusForeign( 'topic', 'start' );
		$result		= $this->reader->getData( FALSE, array( 'id' => "ASC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[1]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->getData( FALSE, array( 'id' => "DESC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[1]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDataWithLimit()
	{
		$this->connection->execute( "INSERT INTO transactions (topic,label) VALUES ('start','getWithLimitTest');" );
		$this->reader->focusForeign( 'topic', 'start' );
		$result		= $this->reader->getData( FALSE, array( 'id' => "ASC" ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->getData( FALSE, array( 'id' => "ASC" ), array( 1, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDataWithNoFocus()
	{
		$assertion	= array();
		$creation	= $this->reader->getData( TRUE );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array();
		$creation	= $this->reader->getData( FALSE );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDBConnection()
	{
		$assertion	= $this->connection;
		$creation	= $this->reader->getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFields'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFields()
	{
		$assertion	= $this->columns;
		$creation	= $this->reader->getFields();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFocus()
	{
		$this->reader->focusPrimary( 1 );
		$assertion	= 1;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();
		$this->reader->focusForeign( 'topic', 'start' );
		$assertion	= array(
			'topic' => 'start'
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$assertion	= 2;
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getForeignKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetForeignKeys()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimaryKey()
	{
		$assertion	= 'id';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$this->reader->setPrimaryKey( 'timestamp' );
		$assertion	= 'timestamp';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTableName()
	{
		$assertion	= "transactions";
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );

		$this->reader->setTableName( "other_table" );

		$assertion	= "other_table";
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isFocused'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFocused()
	{
		$assertion	= "";
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$assertion	= "primary";
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusForeign( 'topic', 'start' );
		$assertion	= "primary";
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();
		$this->reader->focusForeign( 'topic', 'start' );
		$assertion	= "foreign";
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection()
	{
		$dsn = "mysql:host=".$this->host.";dbname=".$this->database;
		$dbc		= new PDO( $dsn, $this->username, $this->password );
		$this->reader->setDBConnection( $dbc );

		$assertion	= $dbc;
		$creation	= $this->reader->getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setFields'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetFields()
	{
		$columns	= array( 'col1', 'col2', 'col3' );

		$this->reader->setFields( $columns );
		
		$assertion	= $columns;
		$creation	= $this->reader->getFields();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setForeignKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetForeignKeys()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setForeignKeys( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getForeignKeys();
		$this->assertEquals( $assertion, $creation );

		$assertion	= FALSE;
		$creation	= $this->reader->setForeignKeys( array( 'topic', 'topic' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKey()
	{
		$assertion	= TRUE;
		$creation	= $this->reader->setPrimaryKey( 'topic' );
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= 'topic';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
		
		$assertion	= FALSE;
		$creation	= $this->reader->setPrimaryKey( 'not_existing' );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTableName()
	{
		$tableName	= "other_table";
		$this->reader->setTableName( $tableName );
		
			
		$assertion	= $tableName;
		$creation	= $this->reader->getTableName();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
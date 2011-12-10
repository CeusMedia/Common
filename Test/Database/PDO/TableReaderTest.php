<?php
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.database.pdo
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_Connection
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2008/11.10.2011
 *	@version		0.1.1
 */
class Test_Database_PDO_TableReaderTest extends PHPUnit_Framework_TestCase
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
		$this->username	= "root";
		$this->password	= "motrada123";
		$this->database	= "test";
		$this->path		= dirname( __FILE__ )."/";
		$this->errorLog	= $this->path."errors.log";
		$this->queryLog	= $this->path."queries.log";

		$this->dsn = "mysql:host=".$this->host.";dbname=".$this->database;

		$options	= array();
		
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

		$this->reader	= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey );
		$this->reader->setIndices( $this->indices );
		$this->reader2	= new Database_PDO_TableReader( $this->connection, $this->tableName2, $this->columns2, $this->primaryKey2 );
		$this->reader2->setIndices( $this->indices2 );
		$this->readerJoin = $this->reader->Join($this->reader2,array('id','id2'));
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
		mysql_query( "DROP TABLE transactions2" );
	}

	/**
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct1()
	{
		$reader		= new Database_PDO_TableReader( $this->connection, "table", array( 'col1', 'col2' ), 'col2', 1 );

		$assertion	= 'table';
		$creation	= $reader->getTableName();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'col1', 'col2' );
		$creation	= $reader->getColumns();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'col2';
		$creation	= $reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$assertion	= array(array( 'col2' , 1 ));
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
		$reader		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 );
	
		$assertion	= array( 'id' => 1 );
		$creation	= array_slice( $reader->get(), 0, 1 );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method '__construct' for Alias.
	 *	@access		public
	 *	@return		void
	 */
	public function testConstruct3()
	{
		$reader		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 , 'sa' );
	
		$assertion	= array( 'id' => 1 );
		$creation	= array_slice( $reader->get(), 0, 1 );
		$this->assertEquals( $assertion, $creation );
		$this->assertEquals($reader->getColumns(),array(
			'sa.id',
			'sa.topic',
			'sa.label',
			'sa.timestamp',
		));
	}
	
	/**
	 *	Test Method Join
	 *	@access		public
	 *	@return		void 
	 */
	public function testJoin1(){
		$reader1		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 );
		$reader2		= new Database_PDO_TableReader( $this->connection, $this->tableName2, $this->columns2, $this->primaryKey2, 1 );
		$reader1 = $reader1->Join($reader2,array('id','id2'));
		$this->assertEquals($reader1->getColumns(),array(
			'transactions.id',
			'transactions.topic',
			'transactions.label',
			'transactions.timestamp',
		));
		$this->assertEquals($reader1->getAlias(),'transactions');
		$this->assertEquals($reader1->getPrimaryKey(),'transactions.id');
		$this->assertEquals($reader1->isJoin(),true);
	}
	/**
	 *	Test Method Join
	 *	@access		public
	 *	@return		void 
	 */
	public function testJoin2(){
		$reader1		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 ,'sa');
		$reader2		= new Database_PDO_TableReader( $this->connection, $this->tableName2, $this->columns2, $this->primaryKey2, 1 );
		$reader1 = $reader1->Join($reader2,array('id','id2'));
		$this->assertEquals($reader1->getColumns(),array(
			'sa.id',
			'sa.topic',
			'sa.label',
			'sa.timestamp',
		));
		$this->assertEquals($reader1->getAlias(),'sa');
		$this->assertEquals($reader1->getPrimaryKey(),'sa.id');
		$this->assertEquals($reader1->isJoin(),true);
	}
	
	/**
	 *	Test Method dejoin
	 *	@access		public
	 *	@return		void 
	 */
	public function testDejoin(){
		$readerJoin = $this->reader->Join( $this->reader2 , array('id','id2') );
		$readerJoin->dejoin();
		$this->assertEquals($readerJoin->getColumns(),array(
			'id',
			'topic',
			'label',
			'timestamp',
		));
		$this->assertEquals($readerJoin->getAlias(),null);
		$this->assertEquals($readerJoin->getPrimaryKey(),'id');
		$this->assertEquals($readerJoin->isJoin(),false);
	}
	
	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$assertion	= 1;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );

		$this->connection->query( "INSERT INTO transactions (label) VALUES ('countTest');" );

		$assertion	= 2;
		$creation	= $this->reader->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $this->reader->count( array( 'label' => 'countTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $this->reader->count( array( 'label' => 'not_existing' ) );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count' for Join
	 *	@access		public
	 *	@return		void
	 */
	public function testCountJoin()
	{
		$readerJoin = $this->reader->Join( $this->reader2 , array('id','id2') );
		$assertion	= 1;
		$creation	= $readerJoin->count();
		$this->assertEquals( $assertion, $creation );

		$this->connection->query( "INSERT INTO transactions (label) VALUES ('countTest');" );
		$this->connection->query( "INSERT INTO transactions2 (label2) VALUES ('countTest');" );

		$assertion	= 2;
		$creation	= $readerJoin->count();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $readerJoin->count( array( 'label' => 'countTest' ) );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 0;
		$creation	= $readerJoin->count( array( 'label' => 'not_existing' ) );
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
		$this->reader->focusIndex( 'topic', 'test' );
		$this->reader->defocus( TRUE );
		
		$assertion[1]  = array( 'topic' , 'test' );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->defocus();

		$assertion	= array();
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'defocus' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocusAlias()
	{
		$reader1		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 ,'sa');
		$reader1->setIndices( $this->indices );
		$reader1->focusPrimary( 2 );
		$reader1->focusIndex( 'sa.topic', 'test' );
		
		$reader1->defocus( TRUE );
		
		$assertion[1]	= array( 'sa.topic' , 'test' ) ;
		$creation	= $reader1->getFocus();
		$this->assertEquals( $assertion, $creation );

		$reader1->defocus();

		$assertion	= array();
		$creation	= $reader1->getFocus();
		$this->assertEquals( $assertion, $creation );
		
		$reader1->focusIndex( 'topic', 'test' );
		
		$reader1->defocus( TRUE );
		
		$assertion	= array( array( 'sa.topic' , 'test' ) );
		$creation	= $reader1->getFocus();
		$this->assertEquals( $assertion, $creation );

		$reader1->defocus();

		$assertion	= array();
		$creation	= $reader1->getFocus();
		$this->assertEquals( $assertion, $creation );

	}
	
	/**
	 *	Tests Method 'defocus' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocusJoin1()
	{
		$reader1		= new Database_PDO_TableReader( $this->connection, $this->tableName, $this->columns, $this->primaryKey, 1 ,'sa');
		$reader1->setIndices( $this->indices );
		$reader1->focusPrimary( 2 );
		$reader1->focusIndex( 'topic', 'test' );
		
		$readerJoin = $reader1->Join( $this->reader2 , array('id','id2') );
		$readerJoin->defocus( TRUE );
		
		$assertion[1]	= array( 'sa.topic' , 'test' ) ;
		$creation	= $readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$readerJoin->defocus();

		$assertion	= array();
		$creation	= $readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

	}
	
	/**
	 *	Tests Method 'defocus' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocusJoin2()
	{
		
		$this->reader->focusPrimary( 2 );
		$this->reader->focusIndex( 'topic', 'test' );
		
		$readerJoin = $this->reader->Join( $this->reader2 , array('id','id2') );
		$readerJoin->defocus( TRUE );
		
		$assertion[1]	= array( 'transactions.topic' , 'test' ) ;
		$creation	= $readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$readerJoin->defocus();

		$assertion	= array();
		$creation	= $readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find();

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( "*" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( "*" );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind4()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( "id" ) );

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
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind5()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( "id" );

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
	 *	Tests Method 'find' for Join
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find();

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find( array( "*" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find( "*" );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin4()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find( array( "id" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
		
		$result		= $this->readerJoin->find( array( "id2" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id2' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin5()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find( array( "transactions.id" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
		
		$result		= $this->readerJoin->find( array( "transactions2.id2" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id2' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindJoin6()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$result		= $this->readerJoin->find( array( "transactions.id","id2" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( 'id','id2' );
		$creation	= array_keys( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithOrder()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( 'id' ), array(), array( 'id' => 'ASC' ) );

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
	 *	Tests Method 'find' ofr Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithOrderJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );	

		$result		= $this->readerJoin->find( array( 'id' ), array(), array( 'id' => 'ASC' ) );

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
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$result		= $this->reader->find( array( 'id' ), array(), array( 'id' => 'DESC' ), array( 0, 1 ) );

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
	 *	Tests Method 'find' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithLimitJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );	

		$result		= $this->readerJoin->find( array( 'id2' ), array(), array( 'transactions2.id2' => 'DESC' ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= array( array( 'id2' => 2 ) );
		$creation	= $result;
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->reader->focusIndex( 'topic', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
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
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->reader->focusPrimary( 1 );										//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
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
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocus3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );

		$this->reader->focusIndex( 'topic', 'test' );							//  will be ignored
		$this->reader->focusPrimary( 1, FALSE );								//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}


	/**
	 *	Tests Method 'find' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocusJoin1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );	
		
		$this->readerJoin->focusIndex( 'topic', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'topic2', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
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
	 *	Tests Method 'find' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocusJoin2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );
		
		$this->readerJoin->focusPrimary( 1 );										//  will be ignored
		$result		= $this->readerJoin->find( array( 'id' ) );

		$assertion	= 2;
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
	 *	Tests Method 'find' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocusJoin3()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );

		$this->readerJoin->focusIndex( 'topic', 'test' );							//  will be ignored
		$this->readerJoin->focusPrimary( 1, FALSE );								//  will be ignored
		$result		= $this->readerJoin->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'find' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWithFocusJoin4()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );	
		
		$this->readerJoin->focusIndex( 'transactions.topic', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'transactions2.topic2', 'start' );							//  will be ignored
		$result		= $this->reader->find( array( 'id' ) );

		$assertion	= 2;
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
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereIn()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'start', 'test' ), array( 'id' => 'ASC' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'test' ) ); 

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
	 *	Tests Method 'findWhereIn' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findTest');" );	

		$result		= $this->readerJoin->findWhereIn( array( 'id' ), "topic", array( 'start', 'test' ), array( 'id' => 'ASC' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereIn( array( 'id' ), "topic2", array( 'test' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
		
		$result		= $this->readerJoin->findWhereIn( array( 'id' ), "transactions2.topic2", array( 'test' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
		
		$result		= $this->readerJoin->findWhereIn( array( 'id' ), "transactions.topic", array( 'test' ) ); 

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
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );

		$result		= $this->reader->findWhereIn( array( 'id' ), "topic", array( 'start', 'test' ), array( 'id' => "DESC" ), array( 0, 1 ) ); 

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
	 *	Tests Method 'findWhereIn' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInWithLimitJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findWhereInTest');" );

		$result		= $this->readerJoin->findWhereIn( array( 'id' ), "topic2", array( 'start', 'test' ), array( 'id' => "DESC" ), array( 0, 1 ) ); 

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
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->findWhereIn( array( 'not_valid' ), "id", 1 );
	}
	
	/**
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->findWhereIn( "*", "not_valid", 1 );
	}
	
	/**
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInExceptionJoin1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->readerJoin->findWhereIn( array( 'transactions2.id' ), "id",array(1) );
	}
	
	/**
	 *	Tests Exception of Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInExceptionJoin2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->readerJoin->findWhereIn( "*", "transactions.id2", array(1) ); //transactions.id2 is not valid
	}
	

	/**
	 *	Tests Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAnd()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'test' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAndWithFocus()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );

		$this->reader->focusIndex( 'topic', 'test' );								//  will be ignored
		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->findWhereInAnd( array( 'id' ), "topic", array( 'start' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAndJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findWhereInAndTest');" );
		
		$result		= $this->readerJoin->findWhereInAnd( array( 'id' ), "topic2", array( 'test' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereInAnd( array( 'id2' ), "topic", array( 'start' ), array( "label" => "findWhereInAndTest" ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAndWithFocusJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findWhereInAndTest');" );

		$this->readerJoin->focusIndex( 'topic', 'test' );								//  will be ignored
		$result		= $this->readerJoin->findWhereInAnd( array( 'id' ), "topic2", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereInAnd( array( 'id2' ), "topic", array( 'start', 'test' ) ); 

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereInAnd( array( 'id' ), "transactions.topic", array( 'start', 'test' ), array( "label" => "findWhereInAndTest" ), array( 'transactions.id' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereInAnd( array( 'transactions2.id2' ), "topic", array( 'test' ), array( "label" => "findWhereInAndTest" ), array( 'transactions2.id2' => 'ASC' ) ); 

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->findWhereInAnd( array( 'id' ), "transactions2.topic2", array( 'start' ), array( "label" => "findWhereInAndTest" ), array( 'id2' => 'ASC' ) ); 

		$assertion	= 0;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndex()
	{
		$this->reader->focusIndex( 'topic', 'test' );
		$assertion[]	= array(
			'topic' , 'test'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'label', 'text' );
		$assertion	= array(array('topic' , 'test'),
							array('label' , 'text'));
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'id', 1 );
		$assertion	= array(array('topic' , 'test'),
							array('label' , 'text'),
							array('id' , 1));
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndex2()
	{
		$this->reader->focusIndex( 'topic', 'test' );
		$assertion[]	= array(
			'topic' , 'test'
			);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'test1' );
		$assertion	= array(array('topic' , 'test1'));
		$creation	= $this->reader->getFocus();

		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'test2',true );
		$assertion	= array(array('topic' , 'test1'),array('topic' , 'test2'));
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
		
		
		$this->reader->focusIndex( 'id', 1 );
		$this->reader->focusIndex( 'id', 2 , true);
		$assertion[]	 =  array('id' , 1);
		$assertion[]	 =  array('id' , 2);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'focusIndex' for join.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndexJoin()
	{
		$this->readerJoin->focusIndex( 'topic', 'test' );
		$assertion[]	= array(
			'transactions.topic' , 'test'
			);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusIndex( 'label', 'text' );
		$assertion[]	= array(
			'transactions.label'	, 'text'
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusIndex( 'id', 1 );
		$assertion[]	= array(
			'transactions.id'	, 1
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'id2', 1 );
		$assertion[]	= array(
			'transactions2.id2'	, 1
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'transactions2.label2', 'test' );
		$assertion[]	= array(
			'transactions2.label2' , 'test'
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndexException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->focusIndex( 'not_an_index', 'not_relevant' );
	}
	
	/**
	 *	Tests Exception of Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndexExceptionJoin()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->readerJoin->focusIndex( 'transactions.label2', 'not_relevant' ); //not an index
	}

	/**
	 *	Tests Method 'focusPrimary'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusPrimary()
	{
		$this->reader->focusPrimary( 2 );
		$assertion[]	= array( 'id' , 2 );
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1 );
		$assertion	=array(array( 'id' , 1 ));
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimary1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->reader->focusPrimary( 1 );
		$result		= $this->reader->get( FALSE );
				
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
		$result		= $this->reader->get();
		
		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimary2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->reader->focusIndex( $this->primaryKey, 1 );
		$result		= $this->reader->get( FALSE );
				
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
		$result		= $this->reader->get();
		
		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get' for Join1.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimaryJoin1()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findWhereInAndTest');" );
		
		$this->readerJoin->focusPrimary( 1 );
		$result		= $this->readerJoin->get( FALSE );
				
		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0]['id'] );
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 2 );
		$result		= $this->readerJoin->get();
		
		$assertion	= 8;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithPrimaryJoin2()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('test','findWhereInAndTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('test','findWhereInAndTest');" );
		
		$this->readerJoin->focusIndex( $this->primaryKey, 1 );
		$result		= $this->readerJoin->get( FALSE );
				
		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= count( $result[0]['id'] );
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 2 );
		$result		= $this->readerJoin->get();
		
		$assertion	= 8;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result['id'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithIndex()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithIndexTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get();

		$assertion	= 4;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->get( FALSE );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'label', 'getWithIndexTest' );
		$result		= $this->reader->get( FALSE );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 4;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'get' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithIndexJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithIndexTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('start','getWithIndexTest');" );
		
		$this->readerJoin->focusIndex( 'topic', 'start' );
		$result		= $this->readerJoin->get();

		$assertion	= 8;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->get( FALSE );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusIndex( 'label2', 'getWithIndexTest' );
		$result		= $this->readerJoin->get( FALSE );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'transactions2.topic2', 'start' );
		$result		= $this->readerJoin->get( FALSE );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithOrders()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithOrderTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ) );

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

		$result		= $this->reader->get( FALSE, array( 'id' => "DESC" ) );

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
	 *	Tests Method 'get' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithOrdersJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithOrderTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('start','getWithOrderTest');" );
				
		$this->readerJoin->focusIndex( 'topic2', 'start' );
		$result		= $this->readerJoin->get( FALSE, array( 'id' => "ASC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
		$creation	= count( $result[0] );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[1]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->get( FALSE, array( 'id2' => "DESC" ) );

		$assertion	= 2;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 8;
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
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithLimit()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithLimitTest');" );
		$this->reader->focusIndex( 'topic', 'start' );
		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->reader->get( FALSE, array( 'id' => "ASC" ), array( 1, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'get' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithLimitJoin()
	{
		$this->connection->query( "INSERT INTO transactions (topic,label) VALUES ('start','getWithLimitTest');" );
		$this->connection->query( "INSERT INTO transactions2 (topic2,label2) VALUES ('start','getWithLimitTest');" );
		
		$this->readerJoin->focusIndex( 'topic', 'start' );
		$result		= $this->readerJoin->get( FALSE, array( 'transactions2.id2' => "ASC" ), array( 0, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 1;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );

		$result		= $this->readerJoin->get( FALSE, array( 'transactions.id' => "ASC" ), array( 1, 1 ) );

		$assertion	= 1;
		$creation	= count( $result );
		$this->assertEquals( $assertion, $creation );

		$assertion	= 2;
		$creation	= $result[0]['id'];
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithNoFocusException()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->reader->get();
	}
	
	/**
	 *	Tests Exception of Method 'get' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetWithNoFocusExceptionJoin()
	{
		$this->setExpectedException( 'RuntimeException' );
		$this->readerJoin->get();
	}

	/**
	 *	Tests Method 'getColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumns()
	{
		$assertion	= $this->columns;
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getColumns' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnsJoin()
	{
		$assertion	= array(
			'transactions.id',
			'transactions.topic',
			'transactions.label',
			'transactions.timestamp',
		);
		$creation	= $this->readerJoin->getColumns();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getColumns' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumnsAlias()
	{
		$reader = $this->reader;
		$reader -> setAlias('sa');
		$assertion	= array(
			'sa.id',
			'sa.topic',
			'sa.label',
			'sa.timestamp',
		);
		$creation	= $reader->getColumns();
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
	 *	Tests Method 'getFocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFocus()
	{
		$this->reader->focusPrimary( 1 );
		$assertion[] = array(
			'id' , 1
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'start' );
		$assertion[] = array(
			'topic' , 'start'
		);
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2, FALSE );
		$assertion[]	=  array('id' , 2) ;
		
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2, TRUE );
		$assertion	=array(array('id' , 2));
		$creation	= $this->reader->getFocus();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getFocus' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFocusJoin()
	{
		$this->readerJoin->focusPrimary( 1 );
		$assertion[]	= array(
			'transactions.id', 1
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusIndex( 'topic2', 'start' );
		$assertion[]	= array(
			'transactions2.topic2' , 'start'
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 2, FALSE );
		$assertion[]	= array(
			'transactions.id' , 2
		);
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 2, TRUE );
		$assertion	= array(array(
			'transactions.id' , 2
		));
		$creation	= $this->readerJoin->getFocus();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndices()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getIndices' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndicesJoin()
	{
		$indices	= array( 'topic', 'timestamp2' );
		$this->readerJoin->setIndices( $indices );

		$assertion	= array( 'transactions.topic', 'transactions2.timestamp2' );
		$creation	= $this->readerJoin->getIndices();
		//print_m($creation);
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->readerJoin->setIndices( $indices );

		$assertion	= array( 'transactions.topic' );
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->readerJoin->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->readerJoin->getIndices();
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
	 *	Tests Method 'getPrimaryKey' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimaryKeyJoin()
	{
		$assertion	= 'transactions.id';
		$creation	= $this->readerJoin->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->setPrimaryKey( 'timestamp' );
		$assertion	= 'transactions.timestamp';
		$creation	= $this->readerJoin->getPrimaryKey();
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
	 *	Tests Method 'getTableName' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTableNameJoin()
	{
		$assertion	= "transactions";
		$creation	= $this->readerJoin->getTableName();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->setTableName( "other_table" );

		$assertion	= "other_table";
		$creation	= $this->readerJoin->getTableName();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'getAlias'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAlias()
	{
		$assertion	= null;
		$this->reader->setAlias($assertion);
		$creation	= $this->reader->getAlias();
		$this->assertEquals( $assertion, $creation );

		$assertion	= 'sa';
		$this->reader->setAlias($assertion);
		$creation	= $this->reader->getAlias();
		$this->assertEquals( $assertion, $creation );
	}
	

	/**
	 *	Tests Method 'isFocused'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFocused()
	{
		$assertion	= FALSE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 2 );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusIndex( 'topic', 'start' );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1, FALSE );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->reader->focusPrimary( 1 );
		$assertion	= TRUE;
		$creation	= $this->reader->isFocused();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'isFocused' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFocusedJoin()
	{
		$assertion	= FALSE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 2 );
		$assertion	= TRUE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusIndex( 'topic2', 'start' );
		$assertion	= TRUE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );
		
		$this->readerJoin->focusIndex( 'topic', 'start' );
		$assertion	= TRUE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 1, FALSE );
		$assertion	= TRUE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );

		$this->readerJoin->focusPrimary( 1 );
		$assertion	= TRUE;
		$creation	= $this->readerJoin->isFocused();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumns()
	{
		$columns	= array( 'col1', 'col2', 'col3' );

		$this->reader->setColumns( $columns );
		
		$assertion	= $columns;
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'setColumns' for Alias.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumnsAlias()
	{
		$columns	= array( 'col1', 'col2', 'col3' );
		
		$this->reader->setAlias('sa');
		$this->reader->setColumns( $columns );
		
		$assertion	= array( 'sa.col1', 'sa.col2', 'sa.col3' );
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
		
		$this->reader->deAlias();
		$assertion	= $columns;
		$creation	= $this->reader->getColumns();
		$this->assertEquals( $assertion, $creation );
		
	}

	/**
	 *	Tests Exception of Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumnsException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setColumns( "string" );
	}

	/**
	 *	Tests Exception of Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumnsException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setColumns( array() );
	}

	/**
	 *	Tests Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection()
	{
		$dbc		= new PDO( $this->dsn, $this->username, $this->password );
		$this->reader->setDBConnection( $dbc );

		$assertion	= $dbc;
		$creation	= $this->reader->getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setDBConnection( "string" );
	}

	/**
	 *	Tests Exception of Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setDBConnection( new Test_Object );
	}

	/**
	 *	Tests Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndices()
	{
		$indices	= array( 'topic', 'timestamp' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array();
		$this->reader->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->reader->getIndices();
		$this->assertEquals( $assertion, $creation );
	}
	
	/**
	 *	Tests Method 'setIndices' for Join.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicesJoin()
	{
		$indices	= array( 'topic', 'timestamp2' );
		$this->readerJoin->setIndices( $indices );

		$assertion	= array( 'transactions.topic', 'transactions2.timestamp2' );
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'topic' );
		$this->readerJoin->setIndices( $indices );

		$assertion	= array( 'transactions.topic' );
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );

		$indices	= array( 'transactions.topic' );
		$this->readerJoin->setIndices( $indices );
		$assertion	= array( 'transactions.topic' );
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );
		
		$indices	= array( 'transactions2.topic2' );
		$this->readerJoin->setIndices( $indices );
		$assertion	= array( 'transactions2.topic2' );
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );
		
		$indices	= array();
		$this->readerJoin->setIndices( $indices );

		$assertion	= $indices;
		$creation	= $this->readerJoin->getIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicesException1()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setIndices( array( 'not_existing' ) );
	}

	/**
	 *	Tests Exception of Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndicesException2()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setIndices( array( 'id' ) );
	}

	/**
	 *	Tests Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKey()
	{
		$this->reader->setPrimaryKey( 'topic' );
		
		$assertion	= 'topic';
		$creation	= $this->reader->getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKeyException()
	{
		$this->setExpectedException( 'InvalidArgumentException' );
		$this->reader->setPrimaryKey( 'not_existing' );
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
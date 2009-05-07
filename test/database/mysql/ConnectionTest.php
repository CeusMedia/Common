<?php
/**
 *	TestUnit of Database_mySQL_Connection.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once '../autoload.php5';
import( 'de.ceus-media.database.mysql.Connection' );
/**
 *	TestUnit of Database_mySQL_Connection.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Connection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Database_mySQL_ConnectionTest extends PHPUnit_Framework_TestCase
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
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'close'.
	 *	@access		public
	 *	@return		void
	 */
	public function testClose()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::close();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getError'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetError()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getError();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getErrNo'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetErrNo()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getErrNo();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'connect'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnect()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::connect();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'connectDatabase'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnectDatabase()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::connectDatabase();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'selectDB'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSelectDB()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::selectDB();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'execute'.
	 *	@access		public
	 *	@return		void
	 */
	public function testExecute()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::execute();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getInsertId'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetInsertId()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getInsertId();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDatabases'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDatabases()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getDatabases();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTables'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTables()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getTables();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'connectPersistant'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConnectPersistant()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::connectPersistant();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAffectedRows'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAffectedRows()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Connection::getAffectedRows();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
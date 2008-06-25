<?php
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.pdo/TableReader' );
/**
 *	TestUnit of Database_PDO_TableReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Database_PDO_TableReaderTest extends PHPUnit_Framework_TestCase
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
		$creation	= Database_PDO_TableReader::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'count'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCount()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'defocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDefocus()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::defocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'find'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFind()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::find();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereIn'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereIn()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::findWhereIn();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'findWhereInAnd'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFindWhereInAnd()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::findWhereInAnd();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusIndex'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusIndex()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::focusIndex();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusPrimary'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusPrimary()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::focusPrimary();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'get'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGet()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::get();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColumns()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getColumns();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetDBConnection()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFocus'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFocus()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetIndices()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPrimaryKey()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetTableName()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::getTableName();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isFocused'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsFocused()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::isFocused();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setColumns'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetColumns()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::setColumns();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setDBConnection'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetDBConnection()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::setDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setIndices'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetIndices()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::setIndices();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setPrimaryKey'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetPrimaryKey()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::setPrimaryKey();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setTableName'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetTableName()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableReader::setTableName();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
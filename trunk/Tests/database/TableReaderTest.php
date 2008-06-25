<?php
/**
 *	TestUnit of Database_TableReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database/TableReader' );
/**
 *	TestUnit of Database_TableReader.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Database_TableReaderTest extends PHPUnit_Framework_TestCase
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
		$creation	= Database_TableReader::__construct();
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
		$creation	= Database_TableReader::defocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'focusForeign'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFocusForeign()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::focusForeign();
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
		$creation	= Database_TableReader::focusPrimary();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllCount()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::getAllCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getAllData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetAllData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::getAllData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::getData();
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
		$creation	= Database_TableReader::getDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getFields'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetFields()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::getFields();
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
		$creation	= Database_TableReader::getFocus();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getForeignKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetForeignKeys()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::getForeignKeys();
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
		$creation	= Database_TableReader::getPrimaryKey();
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
		$creation	= Database_TableReader::getTableName();
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
		$creation	= Database_TableReader::isFocused();
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
		$creation	= Database_TableReader::setDBConnection();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setFields'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetFields()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::setFields();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setForeignKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetForeignKeys()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableReader::setForeignKeys();
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
		$creation	= Database_TableReader::setPrimaryKey();
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
		$creation	= Database_TableReader::setTableName();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
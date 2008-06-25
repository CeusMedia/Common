<?php
/**
 *	TestUnit of Database_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database/TableWriter' );
/**
 *	TestUnit of Database_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
class Tests_Database_TableWriterTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Method 'addData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testAddData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::addData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::deleteData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'deleteDataWhere'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteDataWhere()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::deleteDataWhere();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insertData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsertData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::insertData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'modifyData'.
	 *	@access		public
	 *	@return		void
	 */
	public function testModifyData()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::modifyData();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'modifyDataWhere'.
	 *	@access		public
	 *	@return		void
	 */
	public function testModifyDataWhere()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_TableWriter::modifyDataWhere();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
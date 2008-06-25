<?php
/**
 *	TestUnit of Database_PDO_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_PDO_TableWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.pdo/TableWriter' );
/**
 *	TestUnit of Database_PDO_TableWriter.
 *	@package		Tests.{classPackage}
 *	@extends		PHPUnit_Framework_TestCase
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
	 *	Tests Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDelete()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::delete();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'delete'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		Database_PDO_TableWriter::delete();
	}

	/**
	 *	Tests Method 'deleteByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testDeleteByConditions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::deleteByConditions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'insert'.
	 *	@access		public
	 *	@return		void
	 */
	public function testInsert()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::insert();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdate()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::update();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Exception of Method 'update'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateException()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$this->setExpectedException( 'InvalidArgumentException' );
		Database_PDO_TableWriter::update();
	}

	/**
	 *	Tests Method 'updateByConditions'.
	 *	@access		public
	 *	@return		void
	 */
	public function testUpdateByConditions()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::updateByConditions();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'truncate'.
	 *	@access		public
	 *	@return		void
	 */
	public function testTruncate()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_PDO_TableWriter::truncate();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
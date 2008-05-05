<?php
/**
 *	TestUnit of Database_mySQL_Result.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Result
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.mysql.Result' );
/**
 *	TestUnit of Database_mySQL_Result.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Result
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Tests_Database_mySQL_ResultTest extends PHPUnit_Framework_TestCase
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
		$creation	= Database_mySQL_Result::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchArray()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchNextArray'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchNextArray()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchNextArray();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchNextObject'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchNextObject()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchNextObject();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchNextRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchNextRow()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchNextRow();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchObject'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchObject()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchObject();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'fetchRow'.
	 *	@access		public
	 *	@return		void
	 */
	public function testFetchRow()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::fetchRow();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'recordCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRecordCount()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Result::recordCount();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
<?php
/**
 *	TestUnit of Database_mySQL_TransactionConnection.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_TransactionConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.mysql.TransactionConnection' );
/**
 *	TestUnit of Database_mySQL_TransactionConnection.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_TransactionConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Tests_Database_mySQL_TransactionConnectionTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Method 'start'.
	 *	@access		public
	 *	@return		void
	 */
	public function testStart()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_TransactionConnection::start();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'commit'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCommit()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_TransactionConnection::commit();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rollback'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRollback()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_TransactionConnection::rollback();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
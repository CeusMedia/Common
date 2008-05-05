<?php
/**
 *	TestUnit of Database_BaseConnection.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_BaseConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.BaseConnection' );
/**
 *	TestUnit of Database_BaseConnection.
 *	@package		Tests.database
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_BaseConnection
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Tests_Database_BaseConnectionTest extends PHPUnit_Framework_TestCase
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
		$creation	= Database_BaseConnection::__construct();
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
		$creation	= Database_BaseConnection::connect();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isConnected'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsConnected()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_BaseConnection::isConnected();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setLogFile'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetLogFile()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_BaseConnection::setLogFile();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'setErrorReporting'.
	 *	@access		public
	 *	@return		void
	 */
	public function testSetErrorReporting()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_BaseConnection::setErrorReporting();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
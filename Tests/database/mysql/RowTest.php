<?php
/**
 *	TestUnit of Database_mySQL_Row.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Row
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.database.mysql.Row' );
/**
 *	TestUnit of Database_mySQL_Row.
 *	@package		Tests.database.mysql
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Database_mySQL_Row
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			04.05.2008
 *	@version		0.1
 */
class Tests_Database_mySQL_RowTest extends PHPUnit_Framework_TestCase
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
	 *	Tests Method 'getColCount'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetColCount()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Row::getColCount();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getKeys'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetKeys()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Row::getKeys();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getPairs'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetPairs()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Row::getPairs();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getValue'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValue()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Row::getValue();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getValues'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetValues()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= Database_mySQL_Row::getValues();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
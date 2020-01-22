<?php
/**
 *	TestUnit of DB_Row.
 *	@package		Tests.database
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.05.2008
 *	@version		0.1
 */
require_once dirname( __DIR__ ).'/initLoaders.php';
/**
 *	TestUnit of DB_Row.
 *	@package		Tests.database
 *	@extends		Test_Case
 *	@uses			DB_Row
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			15.05.2008
 *	@version		0.1
 */
class Test_DB_RowTest extends Test_Case
{
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
		$creation	= DB_Row::getColCount();
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
		$creation	= DB_Row::getKeys();
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
		$creation	= DB_Row::getPairs();
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
		$creation	= DB_Row::getValue();
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
		$creation	= DB_Row::getValues();
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
		$creation	= DB_Row::count();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'current'.
	 *	@access		public
	 *	@return		void
	 */
	public function testCurrent()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DB_Row::current();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'key'.
	 *	@access		public
	 *	@return		void
	 */
	public function testKey()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DB_Row::key();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'next'.
	 *	@access		public
	 *	@return		void
	 */
	public function testNext()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DB_Row::next();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'rewind'.
	 *	@access		public
	 *	@return		void
	 */
	public function testRewind()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DB_Row::rewind();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'valid'.
	 *	@access		public
	 *	@return		void
	 */
	public function testValid()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= DB_Row::valid();
		$this->assertEquals( $assertion, $creation );
	}
}

<?php
/**
 *	TestUnit of File_Block_Reader.
 *	@package		Tests.file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Test/initLoaders.php5';
/**
 *	TestUnit of File_Block_Reader.
 *	@package		Tests.file
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Block_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_File_Block_ReaderTest extends PHPUnit_Framework_TestCase
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
		$creation	= File_Block_Reader::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlockNames'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlockNames()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Block_Reader::getBlockNames();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlock()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Block_Reader::getBlock();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'hasBlock'.
	 *	@access		public
	 *	@return		void
	 */
	public function testHasBlock()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Block_Reader::hasBlock();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'getBlocks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testGetBlocks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Block_Reader::getBlocks();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
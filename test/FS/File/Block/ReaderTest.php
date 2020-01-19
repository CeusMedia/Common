<?php
/**
 *	TestUnit of FS_File_Block_Reader.
 *	@package		Tests.file
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once dirname( dirname( dirname( __DIR__ ) ) ).'/initLoaders.php';
/**
 *	TestUnit of FS_File_Block_Reader.
 *	@package		Tests.file
 *	@extends		Test_Case
 *	@uses			FS_File_Block_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_FS_File_Block_ReaderTest extends Test_Case
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
	 *	Tests Method '__construct'.
	 *	@access		public
	 *	@return		void
	 */
	public function test__construct()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= FS_File_Block_Reader::__construct();
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
		$creation	= FS_File_Block_Reader::getBlockNames();
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
		$creation	= FS_File_Block_Reader::getBlock();
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
		$creation	= FS_File_Block_Reader::hasBlock();
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
		$creation	= FS_File_Block_Reader::getBlocks();
		$this->assertEquals( $assertion, $creation );
	}
}
?>

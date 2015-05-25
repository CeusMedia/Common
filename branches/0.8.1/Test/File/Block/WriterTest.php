<?php
/**
 *	TestUnit of File_Block_Writer.
 *	@package		Tests.file.block
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once 'Test/initLoaders.php';
/**
 *	TestUnit of File_Block_Writer.
 *	@package		Tests.file.block
 *	@extends		Test_Case
 *	@uses			File_Block_Writer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_File_Block_WriterTest extends Test_Case
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
		$creation	= File_Block_Writer::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'writeBlocks'.
	 *	@access		public
	 *	@return		void
	 */
	public function testWriteBlocks()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_Block_Writer::writeBlocks();
		$this->assertEquals( $assertion, $creation );
	}
}
?>

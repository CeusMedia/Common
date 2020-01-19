<?php
/**
 *	TestUnit of FS_File_Unicoder.
 *	@package		Tests.
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once dirname( dirname( __DIR__ ) ).'/initLoaders.php';
/**
 *	TestUnit of FS_File_Unicoder.
 *	@package		Tests.
 *	@extends		Test_Case
 *	@uses			FS_File_Unicoder
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Test_FS_File_UnicoderTest extends Test_Case
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
		$creation	= FS_File_Unicoder::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'isUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testIsUnicode()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= FS_File_Unicoder::isUnicode();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'convertToUnicode'.
	 *	@access		public
	 *	@return		void
	 */
	public function testConvertToUnicode()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= FS_File_Unicoder::convertToUnicode();
		$this->assertEquals( $assertion, $creation );
	}
}
?>

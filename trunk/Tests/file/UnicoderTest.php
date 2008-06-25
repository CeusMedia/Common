<?php
/**
 *	TestUnit of File_Unicoder.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Unicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.Unicoder' );
/**
 *	TestUnit of File_Unicoder.
 *	@package		Tests.
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_Unicoder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Tests_File_UnicoderTest extends PHPUnit_Framework_TestCase
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
		$creation	= File_Unicoder::__construct();
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
		$creation	= File_Unicoder::isUnicode();
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
		$creation	= File_Unicoder::convertToUnicode();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
<?php
/**
 *	TestUnit of File_iCal_Builder.
 *	@package		Tests.file_ical
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_iCal_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
require_once( 'PHPUnit/Framework/TestCase.php' ); 
require_once( 'Tests/initLoaders.php5' );
import( 'de.ceus-media.file.ical.Builder' );
/**
 *	TestUnit of File_iCal_Builder.
 *	@package		Tests.file_ical
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			File_iCal_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			19.06.2008
 *	@version		0.1
 */
class Tests_File_iCal_BuilderTest extends PHPUnit_Framework_TestCase
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
		$creation	= File_iCal_Builder::__construct();
		$this->assertEquals( $assertion, $creation );
	}

	/**
	 *	Tests Method 'build'.
	 *	@access		public
	 *	@return		void
	 */
	public function testBuild()
	{
		$this->markTestIncomplete( 'Incomplete Test' );
		$assertion	= TRUE;
		$creation	= File_iCal_Builder::build();
		$this->assertEquals( $assertion, $creation );
	}
}
?>
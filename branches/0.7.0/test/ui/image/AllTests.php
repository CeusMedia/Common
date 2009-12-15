<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_UI_Image_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once 'test/initLoaders.php5';

class Test_UI_Image_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI/Image' );
#		$suite->addTest( Test_UI_Image_service_AllTest::suite() );
		$suite->addTestSuite( 'Test_UI_Image_CaptchaTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_ThumbnailCreatorTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_MedianBlurTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_GaussBlurTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_InverterTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_CreatorTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_ErrorTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_PrinterTest' ); 
		$suite->addTestSuite( 'Test_UI_Image_WatermarkTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_UI_Image_AllTests::main' )
	Test_UI_Image_AllTests::main();
?>

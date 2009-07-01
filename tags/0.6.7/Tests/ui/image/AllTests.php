<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_UI_Image_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/ui/image/CaptchaTest.php' );
require_once( 'Tests/ui/image/ThumbnailCreatorTest.php' );
require_once( 'Tests/ui/image/MedianBlurTest.php' );
require_once( 'Tests/ui/image/GaussBlurTest.php' );
require_once( 'Tests/ui/image/InverterTest.php' );
require_once( 'Tests/ui/image/CreatorTest.php' );
require_once( 'Tests/ui/image/ErrorTest.php' );
require_once( 'Tests/ui/image/PrinterTest.php' );
require_once( 'Tests/ui/image/WatermarkTest.php' );
class Tests_UI_Image_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI/Image' );
#		$suite->addTest( Tests_UI_Image_service_AllTests::suite() );
		$suite->addTestSuite( 'Tests_UI_Image_CaptchaTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_ThumbnailCreatorTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_MedianBlurTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_GaussBlurTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_InverterTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_CreatorTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_ErrorTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_PrinterTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_WatermarkTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_UI_Image_AllTests::main' )
	Tests_UI_Image_AllTests::main();
?>

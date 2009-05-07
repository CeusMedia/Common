<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'UI_Image_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'ui/image/CaptchaTest.php' );
require_once( 'ui/image/ThumbnailCreatorTest.php' );
require_once( 'ui/image/MedianBlurTest.php' );
require_once( 'ui/image/GaussBlurTest.php' );
require_once( 'ui/image/InverterTest.php' );
require_once( 'ui/image/CreatorTest.php' );
require_once( 'ui/image/ErrorTest.php' );
require_once( 'ui/image/PrinterTest.php' );
require_once( 'ui/image/WatermarkTest.php' );
class UI_Image_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI/Image' );
#		$suite->addTest( UI_Image_service_AllTests::suite() );
		$suite->addTestSuite( 'UI_Image_CaptchaTest' ); 
		$suite->addTestSuite( 'UI_Image_ThumbnailCreatorTest' ); 
		$suite->addTestSuite( 'UI_Image_MedianBlurTest' ); 
		$suite->addTestSuite( 'UI_Image_GaussBlurTest' ); 
		$suite->addTestSuite( 'UI_Image_InverterTest' ); 
		$suite->addTestSuite( 'UI_Image_CreatorTest' ); 
		$suite->addTestSuite( 'UI_Image_ErrorTest' ); 
		$suite->addTestSuite( 'UI_Image_PrinterTest' ); 
		$suite->addTestSuite( 'UI_Image_WatermarkTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'UI_Image_AllTests::main' )
	UI_Image_AllTests::main();
?>

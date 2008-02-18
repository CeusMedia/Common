<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_UI_Image_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/ui/image/ThumbnailCreatorTest.php' );
require_once( 'Tests/ui/image/MedianBlurTest.php' );
require_once( 'Tests/ui/image/GaussBlurTest.php' );
require_once( 'Tests/ui/image/InverterTest.php' );
class Tests_UI_Image_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/UI/Image' );
#		$suite->addTest( Tests_UI_Image_service_AllTests::suite() );
		$suite->addTestSuite( 'Tests_UI_Image_ThumbnailCreatorTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_MedianBlurTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_GaussBlurTest' ); 
		$suite->addTestSuite( 'Tests_UI_Image_InverterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_UI_Image_AllTests::main' )
	Tests_UI_Image_AllTests::main();
?>

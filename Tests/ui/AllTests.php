<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_UI_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/ui/html/AllTests.php' );
require_once( 'Tests/ui/image/AllTests.php' );
require_once( 'Tests/ui/TemplateTest.php' );
class Tests_UI_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI' );
		$suite->addTest( Tests_UI_HTML_AllTests::suite() );
		$suite->addTest( Tests_UI_Image_AllTests::suite() );
		$suite->addTestSuite( 'Tests_UI_TemplateTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_UI_AllTests::main' )
	Tests_UI_AllTests::main();
?>

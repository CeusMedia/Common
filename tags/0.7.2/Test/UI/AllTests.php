<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_UI_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_UI_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI' );
		$suite->addTest( Test_UI_HTML_AllTests::suite() );
		$suite->addTest( Test_UI_Image_AllTests::suite() );
		$suite->addTestSuite( 'Test_UI_TemplateTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_UI_AllTests::main' )
	Test_UI_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_UI_HTML_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_UI_HTML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI/HTML' );
#		$suite->addTest( Test_UI_HTML_service_AllTests::suite() );
#		$suite->addTestSuite( 'Test_UI_HTML_ElementsTest' ); 
		$suite->addTestSuite( 'Test_UI_HTML_IndicatorTest' ); 
		$suite->addTestSuite( 'Test_UI_HTML_TagTest' ); 
		$suite->addTestSuite( 'Test_UI_HTML_FormElementsTest' ); 
#		$suite->addTestSuite( 'Test_UI_HTML_WikiParserTest' );					// @todo te be removed in 0.7.1
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_UI_HTML_AllTests::main' )
	Test_UI_HTML_AllTests::main();
?>

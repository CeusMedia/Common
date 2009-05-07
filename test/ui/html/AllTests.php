<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'UI_HTML_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
#require_once( 'ui/html/.../AllTests.php' );
#require_once( 'ui/html/ElementsTest.php' );
require_once( 'ui/html/IndicatorTest.php' );
require_once( 'ui/html/TagTest.php' );
require_once( 'ui/html/FormElementsTest.php' );
require_once( 'ui/html/WikiParserTest.php' );
class UI_HTML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI/HTML' );
#		$suite->addTest( UI_HTML_service_AllTests::suite() );
#		$suite->addTestSuite( 'UI_HTML_ElementsTest' ); 
		$suite->addTestSuite( 'UI_HTML_IndicatorTest' ); 
		$suite->addTestSuite( 'UI_HTML_TagTest' ); 
		$suite->addTestSuite( 'UI_HTML_FormElementsTest' ); 
		$suite->addTestSuite( 'UI_HTML_WikiParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'UI_HTML_AllTests::main' )
	UI_HTML_AllTests::main();
?>

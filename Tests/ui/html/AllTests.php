<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_UI_HTML_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
#require_once( 'Tests/net/html/service/AllTests.php' );
#require_once( 'Tests/net/html/ElementsTest.php' );
class Tests_UI_HTML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/UI/HTML' );
#		$suite->addTest( Tests_UI_HTML_service_AllTests::suite() );
#		$suite->addTestSuite( 'Tests_UI_HTML_ElementsTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_UI_HTML_AllTests::main' )
	Tests_UI_HTML_AllTests::main();
?>

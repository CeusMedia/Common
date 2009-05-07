<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'UI_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'ui/html/AllTests.php' );
require_once( 'ui/image/AllTests.php' );
require_once( 'ui/TemplateTest.php' );
class UI_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/UI' );
		$suite->addTest( UI_HTML_AllTests::suite() );
		$suite->addTest( UI_Image_AllTests::suite() );
		$suite->addTestSuite( 'UI_TemplateTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'UI_AllTests::main' )
	UI_AllTests::main();
?>

<?php
if( !defined('PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_AllTests::main' );

error_reporting( E_ALL ^ E_NOTICE );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once 'Test/initLoaders.php5';
PHPUnit_Util_Filter::addDirectoryToFilter( dirname( __FILE__ ) );
PHPUnit_Util_Filter::addDirectoryToFilter( dirname( dirname( __FILE__ ) ).'/Go' );

class Test_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses' );
		$suite->addTest( Test_ADT_AllTests::suite() );
		$suite->addTest( Test_Alg_AllTests::suite() );
		$suite->addTest( Test_Console_AllTests::suite() );
		$suite->addTest( Test_Database_AllTests::suite() );
		$suite->addTest( Test_File_AllTests::suite() );
		$suite->addTest( Test_Folder_AllTests::suite() );
		$suite->addTest( Test_Math_AllTests::suite() );
		$suite->addTest( Test_Net_AllTests::suite() );
		$suite->addTest( Test_UI_AllTests::suite() );
		$suite->addTest( Test_XML_AllTests::suite() );
		return $suite;
	}
}

if( PHPUnit_MAIN_METHOD == 'Test_AllTests::main' )
	Test_AllTests::main();
?>

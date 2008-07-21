<?php
if( !defined('PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_AllTests::main' );
 
require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/adt/AllTests.php' );
require_once( 'Tests/alg/AllTests.php' );
require_once( 'Tests/database/AllTests.php' );
require_once( 'Tests/file/AllTests.php' );
require_once( 'Tests/folder/AllTests.php' );
require_once( 'Tests/framework/AllTests.php' );
require_once( 'Tests/math/AllTests.php' );
require_once( 'Tests/net/AllTests.php' );
require_once( 'Tests/ui/AllTests.php' );
require_once( 'Tests/xml/AllTests.php' );
require_once( 'Tests/StopWatchTest.php' );

PHPUnit_Util_Filter::addDirectoryToFilter( dirname( __FILE__ ) );

class Tests_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses' );
		$suite->addTest( Tests_ADT_AllTests::suite() );
		$suite->addTest( Tests_Alg_AllTests::suite() );
		$suite->addTest( Tests_Database_AllTests::suite() );
		$suite->addTest( Tests_File_AllTests::suite() );
		$suite->addTest( Tests_Folder_AllTests::suite() );
		$suite->addTest( Tests_Framework_AllTests::suite() );
		$suite->addTest( Tests_Math_AllTests::suite() );
		$suite->addTest( Tests_Net_AllTests::suite() );
		$suite->addTest( Tests_UI_AllTests::suite() );
		$suite->addTest( Tests_XML_AllTests::suite() );
		$suite->addTestSuite( "Tests_StopWatchTest" );
		return $suite;
	}
}

if( PHPUnit_MAIN_METHOD == 'Tests_AllTests::main' )
	Tests_AllTests::main();
?>
<?php
if( !defined('PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'AllTests::main' );

error_reporting( E_ALL ^ E_NOTICE );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'adt/AllTests.php' );
require_once( 'alg/AllTests.php' );
require_once( 'console/AllTests.php' );
require_once( 'database/AllTests.php' );
require_once( 'file/AllTests.php' );
require_once( 'folder/AllTests.php' );
require_once( 'framework/AllTests.php' );
require_once( 'math/AllTests.php' );
require_once( 'net/AllTests.php' );
require_once( 'ui/AllTests.php' );
require_once( 'xml/AllTests.php' );

PHPUnit_Util_Filter::addDirectoryToFilter( dirname( __FILE__ ) );

class AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses' );
		$suite->addTest( ADT_AllTests::suite() );
		$suite->addTest( Alg_AllTests::suite() );
		$suite->addTest( Console_AllTests::suite() );
		$suite->addTest( Database_AllTests::suite() );
		$suite->addTest( File_AllTests::suite() );
		$suite->addTest( Folder_AllTests::suite() );
		$suite->addTest( Framework_AllTests::suite() );
		$suite->addTest( Math_AllTests::suite() );
		$suite->addTest( Net_AllTests::suite() );
		$suite->addTest( UI_AllTests::suite() );
		$suite->addTest( XML_AllTests::suite() );
		return $suite;
	}
}

if( PHPUnit_MAIN_METHOD == 'AllTests::main' )
	AllTests::main();
?>

<?php
if( !defined('PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_AllTests::main' );
 
require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/adt/AllTests.php';
require_once 'Tests/alg/AllTests.php';
require_once 'Tests/file/AllTests.php';
require_once 'Tests/xml/AllTests.php';
require_once 'Tests/framework/AllTests.php';
require_once 'Tests/net/AllTests.php';
require_once 'Tests/ui/AllTests.php';
class Tests_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer' );
		$suite->addTest( Tests_ADT_AllTests::suite() );
		$suite->addTest( Tests_Alg_AllTests::suite() );
		$suite->addTest( Tests_File_AllTests::suite() );
		$suite->addTest( Tests_XML_AllTests::suite() );
		$suite->addTest( Tests_Framework_AllTests::suite() );
		$suite->addTest( Tests_Net_AllTests::suite() );
		$suite->addTest( Tests_UI_AllTests::suite() );
		return $suite;
	}
}

if( PHPUnit_MAIN_METHOD == 'Tests_AllTests::main' )
	Tests_Prototype_AllTests::main();
?>
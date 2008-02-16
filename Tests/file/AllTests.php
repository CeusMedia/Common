<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/file/arc/AllTests.php';
require_once 'Tests/file/list/AllTests.php';
require_once 'Tests/file/yaml/AllTests.php';
class Tests_File_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/ADT' );
		$suite->addTest(Tests_File_Arc_AllTests::suite());
		$suite->addTest(Tests_File_List_AllTests::suite());
		$suite->addTest(Tests_File_Yaml_AllTests::suite());
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_AllTests::main' )
	Tests_File_AllTests::main();
?>

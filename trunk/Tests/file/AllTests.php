<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/file/arc/AllTests.php';
require_once 'Tests/file/configuration/AllTests.php';
require_once 'Tests/file/ical/AllTests.php';
require_once 'Tests/file/ini/AllTests.php';
require_once 'Tests/file/list/AllTests.php';
#require_once 'Tests/file/log/AllTests.php';
require_once 'Tests/file/yaml/AllTests.php';
require_once 'Tests/file/ReaderTest.php';
require_once 'Tests/file/WriterTest.php';

require_once 'Tests/file/NameFilterTest.php';
require_once 'Tests/file/RegexFilterTest.php';
require_once 'Tests/file/RecursiveNameFilterTest.php';
require_once 'Tests/file/RecursiveRegexFilterTest.php';
require_once 'Tests/file/UnicoderTest.php';

class Tests_File_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File' );
		$suite->addTest( Tests_File_Arc_AllTests::suite() );
		$suite->addTest( Tests_File_Configuration_AllTests::suite() );
		$suite->addTest( Tests_File_iCal_AllTests::suite() );
		$suite->addTest( Tests_File_INI_AllTests::suite() );
		$suite->addTest( Tests_File_List_AllTests::suite() );
#		$suite->addTest( Tests_File_Log_AllTests::suite() );
		$suite->addTest( Tests_File_Yaml_AllTests::suite() );
		$suite->addTestSuite('Tests_File_ReaderTest'); 
		$suite->addTestSuite('Tests_File_WriterTest'); 

		$suite->addTestSuite('Tests_File_UnicoderTest'); 
		$suite->addTestSuite('Tests_File_RegexFilterTest'); 
		$suite->addTestSuite('Tests_File_RecursiveNameFilterTest'); 
		$suite->addTestSuite('Tests_File_RecursiveRegexFilterTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_AllTests::main' )
	Tests_File_AllTests::main();
?>

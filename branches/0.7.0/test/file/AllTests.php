<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'file/arc/AllTests.php';
require_once 'file/block/AllTests.php';
require_once 'file/configuration/AllTests.php';
require_once 'file/ical/AllTests.php';
require_once 'file/ini/AllTests.php';
require_once 'file/list/AllTests.php';
require_once 'file/php/AllTests.php';
#require_once 'file/log/AllTests.php';
require_once 'file/yaml/AllTests.php';
require_once 'file/ReaderTest.php';
require_once 'file/EditorTest.php';
require_once 'file/WriterTest.php';

require_once 'file/NameFilterTest.php';
require_once 'file/RegexFilterTest.php';
require_once 'file/RecursiveNameFilterTest.php';
require_once 'file/RecursiveRegexFilterTest.php';
require_once 'file/UnicoderTest.php';
require_once 'file/CacheTest.php';
require_once 'file/StaticCacheTest.php';

class File_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File' );
		$suite->addTest( File_Arc_AllTests::suite() );
		$suite->addTest( File_Block_AllTests::suite() );
		$suite->addTest( File_Configuration_AllTests::suite() );
		$suite->addTest( File_iCal_AllTests::suite() );
		$suite->addTest( File_INI_AllTests::suite() );
		$suite->addTest( File_List_AllTests::suite() );
		$suite->addTest( File_PHP_AllTests::suite() );
#		$suite->addTest( File_Log_AllTests::suite() );
		$suite->addTest( File_Yaml_AllTests::suite() );
		$suite->addTestSuite('File_ReaderTest'); 
		$suite->addTestSuite('File_EditorTest'); 
		$suite->addTestSuite('File_WriterTest'); 

		$suite->addTestSuite('File_RegexFilterTest'); 
		$suite->addTestSuite('File_RecursiveNameFilterTest'); 
		$suite->addTestSuite('File_RecursiveRegexFilterTest'); 
		$suite->addTestSuite('File_UnicoderTest'); 
		$suite->addTestSuite('File_CacheTest'); 
		$suite->addTestSuite('File_StaticCacheTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_AllTests::main' )
	File_AllTests::main();
?>

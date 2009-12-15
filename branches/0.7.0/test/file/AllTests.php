<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_File_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';

class Test_File_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File' );
		$suite->addTest( Test_File_Arc_AllTests::suite() );
		$suite->addTest( Test_File_Block_AllTests::suite() );
		$suite->addTest( Test_File_Configuration_AllTests::suite() );
		$suite->addTest( Test_File_iCal_AllTests::suite() );
		$suite->addTest( Test_File_INI_AllTests::suite() );
		$suite->addTest( Test_File_List_AllTests::suite() );
		$suite->addTest( Test_File_PHP_AllTests::suite() );
#		$suite->addTest( Test_File_Log_AllTests::suite() );
		$suite->addTest( Test_File_Yaml_AllTests::suite() );
		$suite->addTestSuite( 'Test_File_ReaderTest' ); 
		$suite->addTestSuite( 'Test_File_EditorTest' ); 
		$suite->addTestSuite( 'Test_File_WriterTest' ); 

		$suite->addTestSuite( 'Test_File_RegexFilterTest' ); 
		$suite->addTestSuite( 'Test_File_RecursiveNameFilterTest' ); 
		$suite->addTestSuite( 'Test_File_RecursiveRegexFilterTest' ); 
		$suite->addTestSuite( 'Test_File_UnicoderTest' ); 
		$suite->addTestSuite( 'Test_File_CacheTest' ); 
		$suite->addTestSuite( 'Test_File_StaticCacheTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_File_AllTests::main' )
	Test_File_AllTests::main();
?>

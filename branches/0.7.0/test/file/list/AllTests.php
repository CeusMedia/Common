<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_File_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_File_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/List' );
		$suite->addTestSuite( 'Test_File_List_ReaderTest' ); 
		$suite->addTestSuite( 'Test_File_List_WriterTest' ); 
		$suite->addTestSuite( 'Test_File_List_EditorTest' ); 
		$suite->addTestSuite( 'Test_File_List_SectionReaderTest' ); 
		$suite->addTestSuite( 'Test_File_List_SectionWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_File_List_AllTests::main' )
	Test_File_List_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/file/list/ReaderTest.php';
require_once 'Tests/file/list/WriterTest.php';
require_once 'Tests/file/list/EditorTest.php';
require_once 'Tests/file/list/SectionReaderTest.php';
require_once 'Tests/file/list/SectionWriterTest.php';
class Tests_File_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/File/List' );
		$suite->addTestSuite( 'Tests_File_List_ReaderTest' ); 
		$suite->addTestSuite( 'Tests_File_List_WriterTest' ); 
		$suite->addTestSuite( 'Tests_File_List_EditorTest' ); 
		$suite->addTestSuite( 'Tests_File_List_SectionReaderTest' ); 
		$suite->addTestSuite( 'Tests_File_List_SectionWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_List_AllTests::main' )
	Tests_File_List_AllTests::main();
?>

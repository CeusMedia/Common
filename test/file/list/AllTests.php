<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'file/list/ReaderTest.php';
require_once 'file/list/WriterTest.php';
require_once 'file/list/EditorTest.php';
require_once 'file/list/SectionReaderTest.php';
require_once 'file/list/SectionWriterTest.php';
class File_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/List' );
		$suite->addTestSuite( 'File_List_ReaderTest' ); 
		$suite->addTestSuite( 'File_List_WriterTest' ); 
		$suite->addTestSuite( 'File_List_EditorTest' ); 
		$suite->addTestSuite( 'File_List_SectionReaderTest' ); 
		$suite->addTestSuite( 'File_List_SectionWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_List_AllTests::main' )
	File_List_AllTests::main();
?>

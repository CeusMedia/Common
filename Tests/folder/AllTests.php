<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Folder_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
#require_once( 'Tests/folder/arc/AllTests.php' );
require_once( 'Tests/folder/IndexerTest.php' );
require_once( 'Tests/folder/ListerTest.php' );
require_once( 'Tests/folder/NamePatternFinderTest.php' );
require_once( 'Tests/folder/RecursiveIndexerTest.php' );
require_once( 'Tests/folder/RecursiveListerTest.php' );
require_once( 'Tests/folder/RecursiveNamePatternFinderTest.php' );
require_once( 'Tests/folder/ReaderTest.php' );
require_once( 'Tests/folder/EditorTest.php' );
class Tests_Folder_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Folder' );
#		$suite->addTest( Tests_Folder_INI_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Folder_IndexerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_ListerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_NamePatternFinderTest' );
		$suite->addTestSuite( 'Tests_Folder_RecursiveIndexerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_RecursiveListerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_RecursiveNamePatternFinderTest' );
		$suite->addTestSuite( 'Tests_Folder_ReaderTest' ); 
		$suite->addTestSuite( 'Tests_Folder_EditorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Folder_AllTests::main' )
	Tests_Folder_AllTests::main();
?>

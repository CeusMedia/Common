<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Folder_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/folder/IteratorTest.php' );
require_once( 'Tests/folder/ListerTest.php' );
require_once( 'Tests/folder/RegexFilterTest.php' );
require_once( 'Tests/folder/RecursiveIteratorTest.php' );
require_once( 'Tests/folder/RecursiveListerTest.php' );
require_once( 'Tests/folder/RecursiveRegexFilterTest.php' );
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
		$suite->addTestSuite( 'Tests_Folder_IteratorTest' ); 
		$suite->addTestSuite( 'Tests_Folder_ListerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_RegexFilterTest' );
		$suite->addTestSuite( 'Tests_Folder_RecursiveIteratorTest' ); 
		$suite->addTestSuite( 'Tests_Folder_RecursiveListerTest' ); 
		$suite->addTestSuite( 'Tests_Folder_RecursiveRegexFilterTest' );
		$suite->addTestSuite( 'Tests_Folder_ReaderTest' ); 
		$suite->addTestSuite( 'Tests_Folder_EditorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Folder_AllTests::main' )
	Tests_Folder_AllTests::main();
?>

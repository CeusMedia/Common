<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Folder_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'folder/IteratorTest.php' );
require_once( 'folder/ListerTest.php' );
require_once( 'folder/RegexFilterTest.php' );
require_once( 'folder/RecursiveIteratorTest.php' );
require_once( 'folder/RecursiveListerTest.php' );
require_once( 'folder/RecursiveRegexFilterTest.php' );
require_once( 'folder/ReaderTest.php' );
require_once( 'folder/EditorTest.php' );
class Folder_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Folder' );
		$suite->addTestSuite( 'Folder_IteratorTest' ); 
		$suite->addTestSuite( 'Folder_ListerTest' ); 
		$suite->addTestSuite( 'Folder_RegexFilterTest' );
		$suite->addTestSuite( 'Folder_RecursiveIteratorTest' ); 
		$suite->addTestSuite( 'Folder_RecursiveListerTest' ); 
		$suite->addTestSuite( 'Folder_RecursiveRegexFilterTest' );
		$suite->addTestSuite( 'Folder_ReaderTest' ); 
		$suite->addTestSuite( 'Folder_EditorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Folder_AllTests::main' )
	Folder_AllTests::main();
?>

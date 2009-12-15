<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Folder_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once 'test/initLoaders.php5';
class Test_Folder_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Folder' );
		$suite->addTestSuite( 'Test_Folder_IteratorTest' ); 
		$suite->addTestSuite( 'Test_Folder_ListerTest' ); 
		$suite->addTestSuite( 'Test_Folder_RegexFilterTest' );
		$suite->addTestSuite( 'Test_Folder_RecursiveIteratorTest' ); 
		$suite->addTestSuite( 'Test_Folder_RecursiveListerTest' ); 
		$suite->addTestSuite( 'Test_Folder_RecursiveRegexFilterTest' );
		$suite->addTestSuite( 'Test_Folder_ReaderTest' ); 
		$suite->addTestSuite( 'Test_Folder_EditorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Folder_AllTests::main' )
	Test_Folder_AllTests::main();
?>

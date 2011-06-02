<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_File_CSV_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_File_CSV_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/CSV' );
		$suite->addTestSuite( 'Test_File_CSV_IteratorTest' );
		$suite->addTestSuite( 'Test_File_CSV_ReaderTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_File_CSV_AllTests::main' )
	Test_File_CSV_AllTests::main();
?>

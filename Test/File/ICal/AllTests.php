<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_File_ICal_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_File_ICal_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/ICal' );
		$suite->addTestSuite( 'Test_File_ICal_BuilderTest' );
		$suite->addTestSuite( 'Test_File_ICal_ParserTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_File_ICal_AllTests::main' )
	Test_File_ICal_AllTests::main();
?>

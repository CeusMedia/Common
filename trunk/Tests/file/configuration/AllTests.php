<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_Configuration_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/file/configuration/ConverterTest.php' );
require_once( 'Tests/file/configuration/ReaderTest.php' );
class Tests_File_Configuration_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/File/Configuration' );
		$suite->addTestSuite('Tests_File_Configuration_ConverterTest');
		$suite->addTestSuite('Tests_File_Configuration_ReaderTest');
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_Configuration_AllTests::main' )
	Tests_File_Configuration_AllTests::main();
?>
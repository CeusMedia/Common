<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_Configuration_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'file/configuration/ConverterTest.php' );
require_once( 'file/configuration/ReaderTest.php' );
class File_Configuration_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Configuration' );
		$suite->addTestSuite('File_Configuration_ConverterTest');
		$suite->addTestSuite('File_Configuration_ReaderTest');
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_Configuration_AllTests::main' )
	File_Configuration_AllTests::main();
?>
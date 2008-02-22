<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_INI_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/file/ini/ReaderTest.php' );
class Tests_File_INI_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/File/INI' );
		$suite->addTestSuite('Tests_File_INI_ReaderTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_INI_AllTests::main' )
	Tests_File_INI_AllTests::main();
?>

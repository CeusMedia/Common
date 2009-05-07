<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_Log_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'file/log/WriterTest.php' );
class File_Log_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Log' );
		$suite->addTestSuite('File_Log_WriterTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_Log_AllTests::main' )
	File_Log_AllTests::main();
?>

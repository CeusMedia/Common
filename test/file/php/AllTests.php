<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_PHP_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'file/php/ParserTest.php';
require_once 'file/php/MethodVisibilityCheckTest.php';
class File_PHP_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/PHP' );
		$suite->addTestSuite( 'File_PHP_ParserTest' ); 
		$suite->addTestSuite( 'File_PHP_MethodVisibilityCheckTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_PHP_AllTests::main' )
	File_PHP_AllTests::main();
?>

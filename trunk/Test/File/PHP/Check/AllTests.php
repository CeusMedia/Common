<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_File_PHP_Check_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_File_PHP_Check_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/PHP/Check' );
		$suite->addTestSuite( 'Test_File_PHP_Check_MethodVisibilityTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_File_PHP_Check_AllTests::main' )
	Test_File_PHP_Check_AllTests::main();
?>

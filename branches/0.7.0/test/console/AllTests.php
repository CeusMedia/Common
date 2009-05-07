<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Console_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'console/command/AllTests.php';
class Console_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Console' );
		$suite->addTest( Console_Command_AllTests::suite() );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Console_AllTests::main' )
	Console_AllTests::main();
?>
<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Console_Command_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'console/command/ArgumentParserTest.php' );
class Console_Command_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Console/Command' );
		$suite->addTestSuite( 'Console_Command_ArgumentParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Console_Command_AllTests::main' )
	Console_Command_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Alg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'alg/time/ClockTest.php';

final class Alg_Time_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg' );
		$suite->addTestSuite( "Alg_Time_ClockTest" );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Alg_AllTests::main' )
	Alg_AllTests::main();
?>

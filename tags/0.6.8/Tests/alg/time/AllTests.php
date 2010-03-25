<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Alg_Time_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/alg/time/ClockTest.php';
class Tests_Alg_Time_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Time' );
		$suite->addTestSuite( 'Tests_Alg_Time_ClockTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Alg_Time_AllTests::main' )
	Tests_Alg_Time_AllTests::main();
?>

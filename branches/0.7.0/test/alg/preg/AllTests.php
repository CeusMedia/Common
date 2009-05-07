<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Alg_Preg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'alg/preg/MatchTest.php';
class Alg_Preg_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Preg' );
		$suite->addTestSuite( 'Alg_Preg_MatchTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Alg_Preg_AllTests::main' )
	Alg_Preg_AllTests::main();
?>

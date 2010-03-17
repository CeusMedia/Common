<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Alg_PREG_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_Alg_PREG_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/PREG' );
		$suite->addTestSuite( 'Test_Alg_PREG_MatchTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Alg_PREG_AllTests::main' )
	Test_Alg_PREG_AllTests::main();
?>

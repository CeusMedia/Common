<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Alg_Preg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_Alg_Preg_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Preg' );
		$suite->addTestSuite( 'Test_Alg_Preg_MatchTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Alg_Preg_AllTests::main' )
	Test_Alg_Preg_AllTests::main();
?>

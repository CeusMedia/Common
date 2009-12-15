<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Math_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_Math_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Math' );
		$suite->addTest( Test_Math_Algebra_AllTests::suite() );
		$suite->addTest( Test_Math_Finance_AllTests::suite() );
		$suite->addTestSuite( 'Test_Math_CompactIntervalTest' );
		$suite->addTestSuite( 'Test_Math_AverageTest' );
		$suite->addTestSuite( 'Test_Math_FactorialTest' );
		$suite->addTestSuite( 'Test_Math_FormulaTest' );
		$suite->addTestSuite( 'Test_Math_FormulaSumTest' );
		$suite->addTestSuite( 'Test_Math_FormulaProductTest' );
		$suite->addTestSuite( 'Test_Math_PrimeTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Math_AllTests::main' )
	Test_Math_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Math_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'math/algebra/AllTests.php';
require_once 'math/finance/AllTests.php';
require_once 'math/AverageTest.php';
require_once 'math/CompactIntervalTest.php';
require_once 'math/FactorialTest.php';
require_once 'math/FormulaTest.php';
require_once 'math/FormulaSumTest.php';
require_once 'math/FormulaProductTest.php';
require_once 'math/PrimeTest.php';
class Math_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Math' );
		$suite->addTest(Math_Algebra_AllTests::suite());
		$suite->addTest(Math_Finance_AllTests::suite());
		$suite->addTestSuite( 'Math_CompactIntervalTest' );
		$suite->addTestSuite( 'Math_AverageTest' );
		$suite->addTestSuite( 'Math_FactorialTest' );
		$suite->addTestSuite( 'Math_FormulaTest' );
		$suite->addTestSuite( 'Math_FormulaSumTest' );
		$suite->addTestSuite( 'Math_FormulaProductTest' );
		$suite->addTestSuite( 'Math_PrimeTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Math_AllTests::main' )
	Math_AllTests::main();
?>

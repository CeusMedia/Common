<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Math_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/math/algebra/AllTests.php';
require_once 'Tests/math/finance/AllTests.php';
require_once 'Tests/math/AverageTest.php';
require_once 'Tests/math/CompactIntervalTest.php';
require_once 'Tests/math/FactorialTest.php';
require_once 'Tests/math/FormulaTest.php';
require_once 'Tests/math/FormulaSumTest.php';
require_once 'Tests/math/FormulaProductTest.php';
require_once 'Tests/math/PrimeTest.php';
class Tests_Math_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Math' );
		$suite->addTest(Tests_Math_Algebra_AllTests::suite());
		$suite->addTest(Tests_Math_Finance_AllTests::suite());
		$suite->addTestSuite( 'Tests_Math_CompactIntervalTest' );
		$suite->addTestSuite( 'Tests_Math_AverageTest' );
		$suite->addTestSuite( 'Tests_Math_FactorialTest' );
		$suite->addTestSuite( 'Tests_Math_FormulaTest' );
		$suite->addTestSuite( 'Tests_Math_FormulaSumTest' );
		$suite->addTestSuite( 'Tests_Math_FormulaProductTest' );
		$suite->addTestSuite( 'Tests_Math_PrimeTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Math_AllTests::main' )
	Tests_Math_AllTests::main();
?>

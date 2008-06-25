<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Math_Algebra_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/math/algebra/VectorTest.php';
require_once 'Tests/math/algebra/MatrixTest.php';
require_once 'Tests/math/algebra/LabelMatrixTest.php';
class Tests_Math_Algebra_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Math/Algebra' );
		$suite->addTestSuite( 'Tests_Math_Algebra_VectorTest' );
		$suite->addTestSuite( 'Tests_Math_Algebra_MatrixTest' );
		$suite->addTestSuite( 'Tests_Math_Algebra_LabelMatrixTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Math_Algebra_AllTests::main' )
	Tests_Math_Algebra_AllTests::main();
?>

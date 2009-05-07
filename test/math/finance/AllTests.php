<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Math_Finance_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'math/finance/CompoundInterestTest.php';
class Math_Finance_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Math/Finance' );
		$suite->addTestSuite( 'Math_Finance_CompoundInterestTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Math_Finance_AllTests::main' )
	Math_Finance_AllTests::main();
?>

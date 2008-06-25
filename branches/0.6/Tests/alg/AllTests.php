<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Alg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/alg/crypt/AllTests.php';
require_once 'Tests/alg/validation/AllTests.php';
require_once 'Tests/alg/RandomizerTest.php';
require_once 'Tests/alg/StringUnicoderTest.php';
require_once 'Tests/alg/UnitFormaterTest.php';
class Tests_Alg_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/ADT' );
		$suite->addTest( Tests_Alg_Crypt_AllTests::suite() );
		$suite->addTest( Tests_Alg_Validation_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Alg_RandomizerTest' ); 
		$suite->addTestSuite( 'Tests_Alg_StringUnicoderTest' ); 
		$suite->addTestSuite( 'Tests_Alg_UnitFormaterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Alg_AllTests::main' )
	Tests_Alg_AllTests::main();
?>

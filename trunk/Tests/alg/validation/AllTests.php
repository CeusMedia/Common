<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Alg_Validation_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/alg/validation/LanguageValidatorTest.php';
require_once 'Tests/alg/validation/PredicatesTest.php';
require_once 'Tests/alg/validation/PredicateValidatorTest.php';
require_once 'Tests/alg/validation/DefinitionValidatorTest.php';
class Tests_Alg_Validation_AllTests
{
	public static function main( )
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Alg/Validation' );
		$suite->addTestSuite( 'Tests_Alg_Validation_LanguageValidatorTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Validation_PredicatesTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Validation_PredicateValidatorTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Validation_DefinitionValidatorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Alg_Validation_AllTests::main' )
	Tests_Alg_Validation_AllTests::main();
?>
<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Alg_Validation_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'alg/validation/LanguageValidatorTest.php';
require_once 'alg/validation/PredicatesTest.php';
require_once 'alg/validation/PredicateValidatorTest.php';
require_once 'alg/validation/DefinitionValidatorTest.php';
class Alg_Validation_AllTests
{
	public static function main( )
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Validation' );
		$suite->addTestSuite( 'Alg_Validation_LanguageValidatorTest' ); 
		$suite->addTestSuite( 'Alg_Validation_PredicatesTest' ); 
		$suite->addTestSuite( 'Alg_Validation_PredicateValidatorTest' ); 
		$suite->addTestSuite( 'Alg_Validation_DefinitionValidatorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Alg_Validation_AllTests::main' )
	Alg_Validation_AllTests::main();
?>
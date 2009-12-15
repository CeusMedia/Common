<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Alg_Validation_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_Alg_Validation_AllTests
{
	public static function main( )
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Validation' );
		$suite->addTestSuite( 'Test_Alg_Validation_LanguageValidatorTest' ); 
		$suite->addTestSuite( 'Test_Alg_Validation_PredicatesTest' ); 
		$suite->addTestSuite( 'Test_Alg_Validation_PredicateValidatorTest' ); 
		$suite->addTestSuite( 'Test_Alg_Validation_DefinitionValidatorTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Alg_Validation_AllTests::main' )
	Test_Alg_Validation_AllTests::main();
?>
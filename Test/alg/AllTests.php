<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Alg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';

class Test_Alg_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg' );
		$suite->addTest( Test_Alg_Crypt_AllTests::suite() );
		$suite->addTest( Test_Alg_Parcel_AllTests::suite() );
		$suite->addTest( Test_Alg_Preg_AllTests::suite() );
		$suite->addTest( Test_Alg_Validation_AllTests::suite() );
		$suite->addTest( Test_Alg_Time_AllTests::suite() );
		$suite->addTestSuite( 'Test_Alg_HtmlParserTest' ); 
		$suite->addTestSuite( 'Test_Alg_InputFilterTest' ); 
		$suite->addTestSuite( 'Test_Alg_RandomizerTest' ); 
		$suite->addTestSuite( 'Test_Alg_StringUnicoderTest' ); 
		$suite->addTestSuite( 'Test_Alg_TermExtractorTest' ); 
		$suite->addTestSuite( 'Test_Alg_UnitFormaterTest' ); 
		$suite->addTestSuite( 'Test_Alg_CamelCaseTest' ); 
		$suite->addTestSuite( 'Test_Alg_StringTrimmerTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Alg_AllTests::main' )
	Test_Alg_AllTests::main();
?>

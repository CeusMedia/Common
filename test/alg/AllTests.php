<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Alg_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'alg/crypt/AllTests.php';
require_once 'alg/parcel/AllTests.php';
require_once 'alg/preg/AllTests.php';
require_once 'alg/validation/AllTests.php';
require_once 'alg/time/AllTests.php';
require_once 'alg/HtmlParserTest.php';
require_once 'alg/InputFilterTest.php';
require_once 'alg/RandomizerTest.php';
require_once 'alg/StringUnicoderTest.php';
require_once 'alg/TermExtractorTest.php';
require_once 'alg/UnitFormaterTest.php';
require_once 'alg/CamelCaseTest.php';
require_once 'alg/StringTrimmerTest.php';


class Alg_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg' );
		$suite->addTest( Alg_Crypt_AllTests::suite() );
		$suite->addTest( Alg_Parcel_AllTests::suite() );
		$suite->addTest( Alg_Preg_AllTests::suite() );
		$suite->addTest( Alg_Validation_AllTests::suite() );
		$suite->addTest( Alg_Time_AllTests::suite() );
		$suite->addTestSuite( 'Alg_HtmlParserTest' ); 
		$suite->addTestSuite( 'Alg_InputFilterTest' ); 
		$suite->addTestSuite( 'Alg_RandomizerTest' ); 
		$suite->addTestSuite( 'Alg_StringUnicoderTest' ); 
		$suite->addTestSuite( 'Alg_TermExtractorTest' ); 
		$suite->addTestSuite( 'Alg_UnitFormaterTest' ); 
		$suite->addTestSuite( 'Alg_CamelCaseTest' ); 
		$suite->addTestSuite( 'Alg_StringTrimmerTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Alg_AllTests::main' )
	Alg_AllTests::main();
?>

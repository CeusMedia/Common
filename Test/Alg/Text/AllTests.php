<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Alg_Text_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';

class Test_Alg_Text_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Text' );
		$suite->addTestSuite( 'Test_Alg_Text_CamelCaseTest' ); 
		$suite->addTestSuite( 'Test_Alg_Text_FilterTest' ); 
		$suite->addTestSuite( 'Test_Alg_Text_TermExtractorTest' ); 
		$suite->addTestSuite( 'Test_Alg_Text_TrimmerTest' ); 
		$suite->addTestSuite( 'Test_Alg_Text_UnicoderTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Alg_Text_AllTests::main' )
	Test_Alg_Text_AllTests::main();
?>

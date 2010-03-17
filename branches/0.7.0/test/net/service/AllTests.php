<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Net_Service_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_Net_Service_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/Service' );
		$suite->addTest( Test_Net_Service_Definition_AllTests::suite() );
		$suite->addTestSuite( 'Test_Net_Service_ClientTest' );
		$suite->addTestSuite( 'Test_Net_Service_DecoderTest' );
		$suite->addTestSuite( 'Test_Net_Service_HandlerTest' );
		$suite->addTestSuite( 'Test_Net_Service_ParameterValidatorTest' );
		$suite->addTestSuite( 'Test_Net_Service_ResponseTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Net_Service_AllTests::main' )
	Test_Net_Service_AllTests::main();
?>

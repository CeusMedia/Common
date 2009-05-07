<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Net_Service_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'net/service/definition/AllTests.php' );
require_once( 'net/service/ClientTest.php' );
require_once( 'net/service/DecoderTest.php' );
require_once( 'net/service/HandlerTest.php' );
require_once( 'net/service/ParameterValidatorTest.php' );
require_once( 'net/service/ResponseTest.php' );
class Net_Service_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/Service' );
		$suite->addTest( Net_Service_Definition_AllTests::suite() );
		$suite->addTestSuite( 'Net_Service_ClientTest' );
		$suite->addTestSuite( 'Net_Service_DecoderTest' );
		$suite->addTestSuite( 'Net_Service_HandlerTest' );
		$suite->addTestSuite( 'Net_Service_ParameterValidatorTest' );
		$suite->addTestSuite( 'Net_Service_ResponseTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Net_Service_AllTests::main' )
	Net_Service_AllTests::main();
?>

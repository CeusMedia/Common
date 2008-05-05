<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Service_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/service/definition/AllTests.php' );
require_once( 'Tests/service/ClientTest.php' );
require_once( 'Tests/service/HandlerTest.php' );
require_once( 'Tests/service/ParameterValidatorTest.php' );
require_once( 'Tests/service/ResponseTest.php' );
class Tests_Service_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Service' );
		$suite->addTest( Tests_Service_Definition_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Service_ClientTest' );
		$suite->addTestSuite( 'Tests_Service_HandlerTest' );
		$suite->addTestSuite( 'Tests_Service_ParameterValidatorTest' );
		$suite->addTestSuite( 'Tests_Service_ResponseTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Service_AllTests::main' )
	Tests_Service_AllTests::main();
?>

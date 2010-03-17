<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Net_HTTP_Request_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_Net_HTTP_Request_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/HTTP/Request' );
		$suite->addTestSuite( 'Test_Net_HTTP_Request_ReceiverTest' ); 
		$suite->addTestSuite( 'Test_Net_HTTP_Request_ResponseTest' ); 
		$suite->addTestSuite( 'Test_Net_HTTP_Request_SenderTest' ); 
		$suite->addTestSuite( 'Test_Net_HTTP_Request_HeaderTest' ); 
		$suite->addTestSuite( 'Test_Net_HTTP_Request_QueryParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Net_HTTP_Request_AllTests::main' )
	Test_Net_HTTP_Request_AllTests::main();
?>

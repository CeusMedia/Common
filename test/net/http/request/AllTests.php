<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Net_HTTP_Request_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'net/http/request/ReceiverTest.php' );
require_once( 'net/http/request/ResponseTest.php' );
require_once( 'net/http/request/SenderTest.php' );
require_once( 'net/http/request/HeaderTest.php' );
require_once( 'net/http/request/QueryParserTest.php' );
class Net_HTTP_Request_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/HTTP/Request' );
		$suite->addTestSuite( 'Net_HTTP_Request_ReceiverTest' ); 
		$suite->addTestSuite( 'Net_HTTP_Request_ResponseTest' ); 
		$suite->addTestSuite( 'Net_HTTP_Request_SenderTest' ); 
		$suite->addTestSuite( 'Net_HTTP_Request_HeaderTest' ); 
		$suite->addTestSuite( 'Net_HTTP_Request_QueryParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Net_HTTP_Request_AllTests::main' )
	Net_HTTP_Request_AllTests::main();
?>

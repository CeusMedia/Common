<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_HTTP_Request_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/http/request/ReceiverTest.php' );
require_once( 'Tests/net/http/request/ResponseTest.php' );
require_once( 'Tests/net/http/request/SenderTest.php' );
require_once( 'Tests/net/http/request/HeaderTest.php' );
class Tests_Net_HTTP_Request_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Net/HTTP/Request' );
		$suite->addTestSuite( 'Tests_Net_HTTP_Request_ReceiverTest' ); 
		$suite->addTestSuite( 'Tests_Net_HTTP_Request_ResponseTest' ); 
		$suite->addTestSuite( 'Tests_Net_HTTP_Request_SenderTest' ); 
		$suite->addTestSuite( 'Tests_Net_HTTP_Request_HeaderTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_HTTP_Request_AllTests::main' )
	Tests_Net_HTTP_Request_AllTests::main();
?>

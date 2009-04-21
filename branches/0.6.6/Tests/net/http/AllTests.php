<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_HTTP_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/http/request/AllTests.php' );
require_once( 'Tests/net/http/SessionTest.php' );
require_once( 'Tests/net/http/PartitionSessionTest.php' );
require_once( 'Tests/net/http/CharsetSnifferTest.php' );
require_once( 'Tests/net/http/LanguageSnifferTest.php' );
class Tests_Net_HTTP_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/HTTP' );
		$suite->addTest( Tests_Net_HTTP_Request_AllTests::suite() );
#		$suite->addTestSuite( 'Tests_Net_HTTP_SessionTest' ); 
		$suite->addTestSuite( 'Tests_Net_HTTP_PartitionSessionTest' ); 
		$suite->addTestSuite( 'Tests_Net_HTTP_CharsetSnifferTest' );
		$suite->addTestSuite( 'Tests_Net_HTTP_LanguageSnifferTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_HTTP_AllTests::main' )
	Tests_Net_HTTP_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/ftp/AllTests.php' );
require_once( 'Tests/net/http/AllTests.php' );
require_once( 'Tests/net/service/AllTests.php' );
require_once( 'Tests/net/ReaderTest.php' );
class Tests_Net_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net' );
		$suite->addTest( Tests_Net_FTP_AllTests::suite() );
		$suite->addTest( Tests_Net_HTTP_AllTests::suite() );
		$suite->addTest( Tests_Net_Service_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Net_ReaderTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_AllTests::main' )
	Tests_Net_AllTests::main();
?>

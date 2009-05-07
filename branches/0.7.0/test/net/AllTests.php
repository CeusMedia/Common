<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Net_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'net/ftp/AllTests.php' );
require_once( 'net/http/AllTests.php' );
require_once( 'net/service/AllTests.php' );
require_once( 'net/ReaderTest.php' );
class Net_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net' );
		$suite->addTest( Net_FTP_AllTests::suite() );
		$suite->addTest( Net_HTTP_AllTests::suite() );
		$suite->addTest( Net_Service_AllTests::suite() );
		$suite->addTestSuite( 'Net_ReaderTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Net_AllTests::main' )
	Net_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Net_FTP_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'net/ftp/ClientTest.php' );
require_once( 'net/ftp/ConnectionTest.php' );
require_once( 'net/ftp/ReaderTest.php' );
require_once( 'net/ftp/WriterTest.php' );
class Net_FTP_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/FTP' );
		$suite->addTestSuite( 'Net_FTP_ClientTest' );
		$suite->addTestSuite( 'Net_FTP_ConnectionTest' );
		$suite->addTestSuite( 'Net_FTP_ReaderTest' );
		$suite->addTestSuite( 'Net_FTP_WriterTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Net_FTP_AllTests::main' )
	Net_FTP_AllTests::main();
?>

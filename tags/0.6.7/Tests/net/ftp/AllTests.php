<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_FTP_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/ftp/ClientTest.php' );
require_once( 'Tests/net/ftp/ConnectionTest.php' );
require_once( 'Tests/net/ftp/ReaderTest.php' );
require_once( 'Tests/net/ftp/WriterTest.php' );
class Tests_Net_FTP_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/FTP' );
		$suite->addTestSuite( 'Tests_Net_FTP_ClientTest' );
		$suite->addTestSuite( 'Tests_Net_FTP_ConnectionTest' );
		$suite->addTestSuite( 'Tests_Net_FTP_ReaderTest' );
		$suite->addTestSuite( 'Tests_Net_FTP_WriterTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_FTP_AllTests::main' )
	Tests_Net_FTP_AllTests::main();
?>

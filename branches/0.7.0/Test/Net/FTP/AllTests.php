<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Net_FTP_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_Net_FTP_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/FTP' );
		$suite->addTestSuite( 'Test_Net_FTP_ConnectionTest' );
		$suite->addTestSuite( 'Test_Net_FTP_ReaderTest' );
		$suite->addTestSuite( 'Test_Net_FTP_WriterTest' );
		$suite->addTestSuite( 'Test_Net_FTP_ClientTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Net_FTP_AllTests::main' )
	Test_Net_FTP_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Net_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_Net_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net' );
		$suite->addTest( Test_Net_FTP_AllTests::suite() );
		$suite->addTest( Test_Net_HTTP_AllTests::suite() );
		$suite->addTest( Test_Net_Service_AllTests::suite() );
		$suite->addTestSuite( 'Test_Net_ReaderTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Net_AllTests::main' )
	Test_Net_AllTests::main();
?>

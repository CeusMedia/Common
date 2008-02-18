<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/http/AllTests.php' );
class Tests_Net_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Net' );
		$suite->addTest( Tests_Net_HTTP_AllTests::suite() );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_AllTests::main' )
	Tests_Net_AllTests::main();
?>

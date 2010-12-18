<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Net_Service_Parameter_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_Net_Service_Parameter_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/Service/Parameter' );
		$suite->addTestSuite( 'Test_Net_Service_Parameter_ValidatorTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Net_Service_Parameter_AllTests::main' )
	Test_Net_Service_Parameter_AllTests::main();
?>

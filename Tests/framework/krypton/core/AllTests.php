<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Framework_Krypton_Core_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/framework/krypton/core/TemplateTest.php';
class Tests_Framework_Krypton_Core_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Framework/Krypton' );
		$suite->addTestSuite( 'Tests_Framework_Krypton_Core_TemplateTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Framework_Krypton_Core_AllTests::main' )
	Tests_Framework_Krypton_Core_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'ADT_JSON_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'adt/json/BuilderTest.php';
class ADT_JSON_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/JSON' );
		$suite->addTestSuite('ADT_JSON_BuilderTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'ADT_JSON_AllTests::main' )
	ADT_JSON_AllTests::main();
?>

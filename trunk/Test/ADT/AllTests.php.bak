<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_ADT_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once 'Test/initLoaders.php5';
class Test_ADT_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT' );
		$suite->addTest(Test_ADT_List_AllTests::suite());
		$suite->addTest(Test_ADT_Tree_AllTests::suite());
		$suite->addTest(Test_ADT_JSON_AllTests::suite());
		$suite->addTestSuite( "Test_ADT_OptionObjectTest" ); 
		$suite->addTestSuite( "Test_ADT_RegistryTest" ); 
		$suite->addTestSuite( "Test_ADT_ObjectTest" );
		$suite->addTestSuite( "Test_ADT_StringBufferTest" );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_ADT_AllTests::main' )
	Test_ADT_AllTests::main();
?>

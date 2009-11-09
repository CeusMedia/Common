<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'ADT_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'test/adt/list/AllTests.php' );
require_once( 'adt/tree/AllTests.php' );
require_once( 'adt/json/AllTests.php' );
require_once( 'adt/OptionObjectTest.php' );
require_once( 'adt/ReferenceTest.php' );
require_once( 'adt/RegistryTest.php' );
require_once( 'adt/ObjectTest.php' );
require_once( 'adt/StringBufferTest.php' );
class ADT_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT' );
		$suite->addTest(ADT_List_AllTests::suite());
		$suite->addTest(ADT_Tree_AllTests::suite());
		$suite->addTest(ADT_JSON_AllTests::suite());
		$suite->addTestSuite( "ADT_OptionObjectTest" ); 
		$suite->addTestSuite( "ADT_ReferenceTest" ); 
		$suite->addTestSuite( "ADT_RegistryTest" ); 
		$suite->addTestSuite( "ADT_ObjectTest" );
		$suite->addTestSuite( "ADT_StringBufferTest" );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'ADT_AllTests::main' )
	ADT_AllTests::main();
?>

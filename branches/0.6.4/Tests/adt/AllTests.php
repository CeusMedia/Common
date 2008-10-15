<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_ADT_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/adt/list/AllTests.php' );
require_once( 'Tests/adt/tree/AllTests.php' );
require_once( 'Tests/adt/json/AllTests.php' );
require_once( 'Tests/adt/OptionObjectTest.php' );
require_once( 'Tests/adt/ReferenceTest.php' );
require_once( 'Tests/adt/RegistryTest.php' );
require_once( 'Tests/adt/ObjectTest.php' );
require_once( 'Tests/adt/StringBufferTest.php' );
class Tests_ADT_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT' );
		$suite->addTest(Tests_ADT_List_AllTests::suite());
		$suite->addTest(Tests_ADT_Tree_AllTests::suite());
		$suite->addTest(Tests_ADT_JSON_AllTests::suite());
		$suite->addTestSuite( "Tests_ADT_OptionObjectTest" ); 
		$suite->addTestSuite( "Tests_ADT_ReferenceTest" ); 
		$suite->addTestSuite( "Tests_ADT_RegistryTest" ); 
		$suite->addTestSuite( "Tests_ADT_ObjectTest" );
		$suite->addTestSuite( "Tests_ADT_StringBufferTest" );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_ADT_AllTests::main' )
	Tests_ADT_AllTests::main();
?>

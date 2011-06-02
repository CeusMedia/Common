<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_ADT_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_ADT_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/List' );
		$suite->addTestSuite('Test_ADT_List_DictionaryTest'); 
		$suite->addTestSuite('Test_ADT_List_LevelMapTest'); 
		$suite->addTestSuite('Test_ADT_List_SectionListTest'); 
		$suite->addTestSuite('Test_ADT_List_StackTest'); 
		$suite->addTestSuite('Test_ADT_List_QueueTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_ADT_List_AllTests::main' )
	Test_ADT_List_AllTests::main();
?>

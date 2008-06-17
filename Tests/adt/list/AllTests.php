<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_ADT_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/adt/list/DictionaryTest.php';
require_once 'Tests/adt/list/LevelMapTest.php';
require_once 'Tests/adt/list/SectionListTest.php';
require_once 'Tests/adt/list/StackTest.php';
require_once 'Tests/adt/list/QueueTest.php';
class Tests_ADT_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/ADT/List' );
		$suite->addTestSuite('Tests_ADT_List_DictionaryTest'); 
		$suite->addTestSuite('Tests_ADT_List_LevelMapTest'); 
		$suite->addTestSuite('Tests_ADT_List_SectionListTest'); 
		$suite->addTestSuite('Tests_ADT_List_StackTest'); 
		$suite->addTestSuite('Tests_ADT_List_QueueTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_ADT_List_AllTests::main' )
	Tests_ADT_List_AllTests::main();
?>

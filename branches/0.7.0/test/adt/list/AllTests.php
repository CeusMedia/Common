<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'ADT_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'adt/list/DictionaryTest.php';
require_once 'adt/list/LevelMapTest.php';
require_once 'adt/list/SectionListTest.php';
require_once 'adt/list/StackTest.php';
require_once 'adt/list/QueueTest.php';
class ADT_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/List' );
		$suite->addTestSuite('ADT_List_DictionaryTest'); 
		$suite->addTestSuite('ADT_List_LevelMapTest'); 
		$suite->addTestSuite('ADT_List_SectionListTest'); 
		$suite->addTestSuite('ADT_List_StackTest'); 
		$suite->addTestSuite('ADT_List_QueueTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'ADT_List_AllTests::main' )
	ADT_List_AllTests::main();
?>

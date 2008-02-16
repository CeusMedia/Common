<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_ADT_List_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/adt/list/LinkListTest.php';
require_once 'Tests/adt/list/SectionListTest.php';
require_once 'Tests/adt/list/DictionaryTest.php';
class Tests_ADT_List_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/ADT/List' );
		$suite->addTestSuite('Tests_ADT_List_LinkListTest'); 
		$suite->addTestSuite('Tests_ADT_List_SectionListTest'); 
		$suite->addTestSuite('Tests_ADT_List_DictionaryTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_ADT_List_AllTests::main' )
	Tests_ADT_List_AllTests::main();
?>

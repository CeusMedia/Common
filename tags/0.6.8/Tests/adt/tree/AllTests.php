<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_ADT_Tree_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/adt/tree/BinaryNodeTest.php';
require_once 'Tests/adt/tree/BalanceBinaryNodeTest.php';
require_once 'Tests/adt/tree/NodeTest.php';
//require_once 'Tests/adt/tree/AvlNodeTest.php';
class Tests_ADT_Tree_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/Tree' );
		$suite->addTestSuite('Tests_ADT_Tree_BinaryNodeTest'); 
		$suite->addTestSuite('Tests_ADT_Tree_BalanceBinaryNodeTest'); 
		$suite->addTestSuite('Tests_ADT_Tree_NodeTest'); 
//		$suite->aadTestSuite('Tests_ADT_Tree_AvlNodeTest');
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_ADT_Tree_AllTests::main' )
	Tests_ADT_Tree_AllTests::main();
?>

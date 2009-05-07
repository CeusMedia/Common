<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'ADT_Tree_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'adt/tree/BinaryNodeTest.php';
require_once 'adt/tree/BalanceBinaryNodeTest.php';
require_once 'adt/tree/NodeTest.php';
class ADT_Tree_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/Tree' );
		$suite->addTestSuite('ADT_Tree_BinaryNodeTest'); 
		$suite->addTestSuite('ADT_Tree_BalanceBinaryNodeTest'); 
		$suite->addTestSuite('ADT_Tree_NodeTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'ADT_Tree_AllTests::main' )
	ADT_Tree_AllTests::main();
?>

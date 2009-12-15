<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_ADT_Tree_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_ADT_Tree_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/ADT/Tree' );
		$suite->addTestSuite('Test_ADT_Tree_BinaryNodeTest'); 
		$suite->addTestSuite('Test_ADT_Tree_BalanceBinaryNodeTest'); 
		$suite->addTestSuite('Test_ADT_Tree_NodeTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_ADT_Tree_AllTests::main' )
	Test_ADT_Tree_AllTests::main();
?>

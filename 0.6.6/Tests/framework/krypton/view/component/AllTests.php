<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Framework_Krypton_View_Component_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/framework/krypton/view/component/TemplateTest.php';
class Tests_Framework_Krypton_View_Component_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Framework/Krypton/View/Component' );
		$suite->addTestSuite( 'Tests_Framework_Krypton_View_Component_TemplateTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Framework_Krypton_View_Component_AllTests::main' )
	Tests_Framework_Krypton_View_Component_AllTests::main();
?>

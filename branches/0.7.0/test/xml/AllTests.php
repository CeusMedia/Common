<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_XML_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_XML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML' );
		$suite->addTest( Test_XML_Atom_AllTests::suite() );
		$suite->addTest( Test_XML_DOM_AllTests::suite() );
		$suite->addTest( Test_XML_RSS_AllTests::suite() );
		$suite->addTest( Test_XML_WDDX_AllTests::suite() );
		$suite->addTest( Test_XML_XSL_AllTests::suite() );
		$suite->addTestSuite( 'Test_XML_ElementTest' ); 
		$suite->addTestSuite( 'Test_XML_ElementReaderTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_XML_AllTests::main' )
	Test_XML_AllTests::main();
?>

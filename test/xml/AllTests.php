<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'xml/atom/AllTests.php' );
require_once( 'xml/dom/AllTests.php' );
require_once( 'xml/xsl/AllTests.php' );
require_once( 'xml/rss/AllTests.php' );
require_once( 'xml/wddx/AllTests.php' );
require_once( 'xml/ElementTest.php' );
require_once( 'xml/ElementReaderTest.php' );
class XML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML' );
		$suite->addTest( XML_Atom_AllTests::suite() );
		$suite->addTest( XML_DOM_AllTests::suite() );
		$suite->addTest( XML_RSS_AllTests::suite() );
		$suite->addTest( XML_WDDX_AllTests::suite() );
		$suite->addTest( XML_XSL_AllTests::suite() );
		$suite->addTestSuite( 'Xml_ElementTest' ); 
		$suite->addTestSuite( 'Xml_ElementReaderTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_AllTests::main' )
	File_AllTests::main();
?>

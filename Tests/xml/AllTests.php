<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_XML_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/xml/atom/AllTests.php' );
require_once( 'Tests/xml/dom/AllTests.php' );
require_once( 'Tests/xml/xsl/AllTests.php' );
require_once( 'Tests/xml/rss/AllTests.php' );
require_once( 'Tests/xml/wddx/AllTests.php' );
require_once( 'Tests/xml/ElementTest.php' );
require_once( 'Tests/xml/ElementReaderTest.php' );
class Tests_XML_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/XML' );
		$suite->addTest( Tests_XML_Atom_AllTests::suite() );
		$suite->addTest( Tests_XML_DOM_AllTests::suite() );
		$suite->addTest( Tests_XML_RSS_AllTests::suite() );
		$suite->addTest( Tests_XML_WDDX_AllTests::suite() );
		$suite->addTest( Tests_XML_XSL_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Xml_ElementTest' ); 
		$suite->addTestSuite( 'Tests_Xml_ElementReaderTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_XML_AllTests::main' )
	Tests_File_AllTests::main();
?>

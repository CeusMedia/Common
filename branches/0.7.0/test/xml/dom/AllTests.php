<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_DOM_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'xml/dom/pear/AllTests.php' );
require_once( 'xml/dom/NodeTest.php' );
require_once( 'xml/dom/BuilderTest.php' );
require_once( 'xml/dom/ParserTest.php' );
require_once( 'xml/dom/FileReaderTest.php' );
require_once( 'xml/dom/FileWriterTest.php' );
require_once( 'xml/dom/ObjectSerializerTest.php' );
require_once( 'xml/dom/ObjectDeserializerTest.php' );
require_once( 'xml/dom/StorageTest.php' );
require_once( 'xml/dom/XPathQueryTest.php' );
require_once( 'xml/dom/GoogleSitemapBuilderTest.php' );
require_once( 'xml/dom/GoogleSitemapWriterTest.php' );
class XML_DOM_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/DOM' );
		$suite->addTest( XML_DOM_PEAR_AllTests::suite() );
		$suite->addTestSuite( 'XML_DOM_NodeTest' ); 
		$suite->addTestSuite( 'XML_DOM_BuilderTest' ); 
		$suite->addTestSuite( 'XML_DOM_ParserTest' ); 
		$suite->addTestSuite( 'XML_DOM_FileReaderTest' ); 
		$suite->addTestSuite( 'XML_DOM_FileWriterTest' ); 
		$suite->addTestSuite( 'XML_DOM_ObjectSerializerTest' ); 
		$suite->addTestSuite( 'XML_DOM_ObjectDeserializerTest' ); 
		$suite->addTestSuite( 'XML_DOM_StorageTest' ); 
		$suite->addTestSuite( 'XML_DOM_XpathQueryTest' ); 
		$suite->addTestSuite( 'XML_DOM_GoogleSitemapBuilderTest' ); 
		$suite->addTestSuite( 'XML_DOM_GoogleSitemapWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_DOM_AllTests::main' )
	XML_DOM_AllTests::main();
?>

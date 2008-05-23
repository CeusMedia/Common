<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_XML_DOM_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/xml/dom/NodeTest.php' );
require_once( 'Tests/xml/dom/BuilderTest.php' );
require_once( 'Tests/xml/dom/ParserTest.php' );
require_once( 'Tests/xml/dom/FileReaderTest.php' );
require_once( 'Tests/xml/dom/FileWriterTest.php' );
require_once( 'Tests/xml/dom/ObjectSerializerTest.php' );
require_once( 'Tests/xml/dom/ObjectDeserializerTest.php' );
require_once( 'Tests/xml/dom/StorageTest.php' );
require_once( 'Tests/xml/dom/XPathQueryTest.php' );
require_once( 'Tests/xml/dom/GoogleSitemapBuilderTest.php' );
require_once( 'Tests/xml/dom/GoogleSitemapWriterTest.php' );
class Tests_XML_DOM_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/XML/DOM' );
		$suite->addTestSuite( 'Tests_XML_DOM_NodeTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_BuilderTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_ParserTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_FileReaderTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_FileWriterTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_ObjectSerializerTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_ObjectDeserializerTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_StorageTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_XpathQueryTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_GoogleSitemapBuilderTest' ); 
		$suite->addTestSuite( 'Tests_XML_DOM_GoogleSitemapWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_XML_DOM_AllTests::main' )
	Tests_XML_DOM_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_XML_DOM_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_XML_DOM_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/DOM' );
		$suite->addTest( Test_XML_DOM_PEAR_AllTests::suite() );
		$suite->addTestSuite( 'Test_XML_DOM_NodeTest' );
		$suite->addTestSuite( 'Test_XML_DOM_BuilderTest' );
		$suite->addTestSuite( 'Test_XML_DOM_ParserTest' );
		$suite->addTestSuite( 'Test_XML_DOM_FileReaderTest' );
		$suite->addTestSuite( 'Test_XML_DOM_FileWriterTest' );
		$suite->addTestSuite( 'Test_XML_DOM_ObjectSerializerTest' );
		$suite->addTestSuite( 'Test_XML_DOM_ObjectDeserializerTest' );
		$suite->addTestSuite( 'Test_XML_DOM_StorageTest' );
		$suite->addTestSuite( 'Test_XML_DOM_XPathQueryTest' );
		$suite->addTestSuite( 'Test_XML_DOM_GoogleSitemapBuilderTest' );
		$suite->addTestSuite( 'Test_XML_DOM_GoogleSitemapWriterTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_XML_DOM_AllTests::main' )
	Test_XML_DOM_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_XML_RSS_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_XML_RSS_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/RSS' );
		$suite->addTestSuite( 'Test_XML_RSS_ReaderTest' ); 
		$suite->addTestSuite( 'Test_XML_RSS_ParserTest' ); 
		$suite->addTestSuite( 'Test_XML_RSS_BuilderTest' ); 
		$suite->addTestSuite( 'Test_XML_RSS_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_XML_RSS_AllTests::main' )
	Test_XML_RSS_AllTests::main();
?>

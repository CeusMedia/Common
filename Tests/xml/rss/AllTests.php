<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_XML_RSS_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/xml/rss/ReaderTest.php' );
require_once( 'Tests/xml/rss/ParserTest.php' );
require_once( 'Tests/xml/rss/BuilderTest.php' );
require_once( 'Tests/xml/rss/WriterTest.php' );
class Tests_XML_RSS_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/XML/RSS' );
		$suite->addTestSuite( 'Tests_XML_RSS_ReaderTest' ); 
		$suite->addTestSuite( 'Tests_XML_RSS_ParserTest' ); 
		$suite->addTestSuite( 'Tests_XML_RSS_BuilderTest' ); 
		$suite->addTestSuite( 'Tests_XML_RSS_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_XML_RSS_AllTests::main' )
	Tests_XML_RSS_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_RSS_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'xml/rss/ReaderTest.php' );
require_once( 'xml/rss/ParserTest.php' );
require_once( 'xml/rss/BuilderTest.php' );
require_once( 'xml/rss/WriterTest.php' );
class XML_RSS_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/RSS' );
		$suite->addTestSuite( 'XML_RSS_ReaderTest' ); 
		$suite->addTestSuite( 'XML_RSS_ParserTest' ); 
		$suite->addTestSuite( 'XML_RSS_BuilderTest' ); 
		$suite->addTestSuite( 'XML_RSS_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_RSS_AllTests::main' )
	XML_RSS_AllTests::main();
?>

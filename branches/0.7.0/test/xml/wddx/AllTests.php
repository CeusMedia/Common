<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_WDDX_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'xml/wddx/BuilderTest.php' );
require_once( 'xml/wddx/FileReaderTest.php' );
require_once( 'xml/wddx/FileWriterTest.php' );
require_once( 'xml/wddx/ParserTest.php' );
class XML_WDDX_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/WDDX' );
		$suite->addTestSuite( 'XML_WDDX_BuilderTest' ); 
		$suite->addTestSuite( 'XML_WDDX_FileReaderTest' ); 
		$suite->addTestSuite( 'XML_WDDX_FileWriterTest' ); 
		$suite->addTestSuite( 'XML_WDDX_ParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_WDDX_AllTests::main' )
	XML_WDDX_AllTests::main();
?>

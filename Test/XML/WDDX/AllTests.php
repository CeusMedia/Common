<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_XML_WDDX_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Test/initLoaders.php5';
class Test_XML_WDDX_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/WDDX' );
		$suite->addTestSuite( 'Test_XML_WDDX_BuilderTest' ); 
		$suite->addTestSuite( 'Test_XML_WDDX_FileReaderTest' ); 
		$suite->addTestSuite( 'Test_XML_WDDX_FileWriterTest' ); 
		$suite->addTestSuite( 'Test_XML_WDDX_ParserTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_XML_WDDX_AllTests::main' )
	Test_XML_WDDX_AllTests::main();
?>

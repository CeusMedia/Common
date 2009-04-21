<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Net_Service_Definition_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/net/service/definition/ConverterTest.php' );
require_once( 'Tests/net/service/definition/LoaderTest.php' );
require_once( 'Tests/net/service/definition/XmlReaderTest.php' );
require_once( 'Tests/net/service/definition/XmlWriterTest.php' );
class Tests_Net_Service_Definition_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Net/Service/Definition' );
		$suite->addTestSuite( 'Tests_Net_Service_Definition_ConverterTest' );
		$suite->addTestSuite( 'Tests_Net_Service_Definition_LoaderTest' );
		$suite->addTestSuite( 'Tests_Net_Service_Definition_XmlReaderTest' );
		$suite->addTestSuite( 'Tests_Net_Service_Definition_XmlWriterTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Net_Service_Definition_AllTests::main' )
	Tests_Net_Service_Definition_AllTests::main();
?>

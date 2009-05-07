<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_DOM_PEAR_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'xml/dom/pear/PackageReaderTest.php' );
class XML_DOM_PEAR_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/DOM/PEAR' );
		$suite->addTestSuite( 'XML_DOM_PEAR_PackageReaderTest' );
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_DOM_PEAR_AllTests::main' )
	XML_DOM_PEAR_AllTests::main();
?>

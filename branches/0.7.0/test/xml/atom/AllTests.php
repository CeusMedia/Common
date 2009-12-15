<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_XML_Atom_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'test/initLoaders.php5';
class Test_XML_Atom_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/Atom' );
		$suite->addTestSuite( 'Test_XML_Atom_ValidatorTest' ); 
		$suite->addTestSuite( 'Test_XML_Atom_ParserTest' ); 
		$suite->addTestSuite( 'Test_XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'Test_XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'Test_XML_Atom_BuilderTest' ); 
#		$suite->addTestSuite( 'Test_XML_Atom_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_XML_Atom_AllTests::main' )
	Test_XML_Atom_AllTests::main();
?>
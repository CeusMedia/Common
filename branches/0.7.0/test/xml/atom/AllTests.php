<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'XML_Atom_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'xml/atom/ValidatorTest.php' );
require_once( 'xml/atom/ParserTest.php' );
require_once( 'xml/atom/ReaderTest.php' );
#require_once( 'xml/atom/BuilderTest.php' );
#require_once( 'xml/atom/WriterTest.php' );
class XML_Atom_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/XML/Atom' );
		$suite->addTestSuite( 'XML_Atom_ValidatorTest' ); 
		$suite->addTestSuite( 'XML_Atom_ParserTest' ); 
		$suite->addTestSuite( 'XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'XML_Atom_BuilderTest' ); 
#		$suite->addTestSuite( 'XML_Atom_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'XML_Atom_AllTests::main' )
	XML_Atom_AllTests::main();
?>
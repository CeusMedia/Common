<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_XML_Atom_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/xml/atom/ValidatorTest.php' );
require_once( 'Tests/xml/atom/ParserTest.php' );
require_once( 'Tests/xml/atom/ReaderTest.php' );
#require_once( 'Tests/xml/atom/BuilderTest.php' );
#require_once( 'Tests/xml/atom/WriterTest.php' );
class Tests_XML_Atom_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/XML/Atom' );
		$suite->addTestSuite( 'Tests_XML_Atom_ValidatorTest' ); 
		$suite->addTestSuite( 'Tests_XML_Atom_ParserTest' ); 
		$suite->addTestSuite( 'Tests_XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'Tests_XML_Atom_ReaderTest' ); 
#		$suite->addTestSuite( 'Tests_XML_Atom_BuilderTest' ); 
#		$suite->addTestSuite( 'Tests_XML_Atom_WriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_XML_Atom_AllTests::main' )
	Tests_XML_Atom_AllTests::main();
?>
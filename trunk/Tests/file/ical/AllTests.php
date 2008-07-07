<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_iCal_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/initLoaders.php5' );
require_once( 'Tests/file/ical/BuilderTest.php' );
require_once( 'Tests/file/ical/ParserTest.php' );
class Tests_File_iCal_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/iCal' );
		$suite->addTestSuite('Tests_File_iCal_BuilderTest'); 
		$suite->addTestSuite('Tests_File_iCal_ParserTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_iCal_AllTests::main' )
	Tests_File_iCal_AllTests::main();
?>

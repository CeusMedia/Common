<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_Arc_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/file/arc/BzipTest.php';
require_once 'Tests/file/arc/GzipTest.php';
require_once 'Tests/file/arc/TarTest.php';
class Tests_File_Arc_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Arc' );
		$suite->addTestSuite('Tests_File_Arc_BzipTest'); 
		$suite->addTestSuite('Tests_File_Arc_GzipTest'); 
		$suite->addTestSuite('Tests_File_Arc_TarTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_Arc_AllTests::main' )
	Tests_File_Arc_AllTests::main();
?>

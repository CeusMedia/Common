<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_Arc_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/file/arc/BzipFileTest.php';
require_once 'Tests/file/arc/GzipFileTest.php';
require_once 'Tests/file/arc/TarFileTest.php';
class Tests_File_Arc_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Arc' );
		$suite->addTestSuite('Tests_File_Arc_BzipFileTest'); 
		$suite->addTestSuite('Tests_File_Arc_GzipFileTest'); 
		$suite->addTestSuite('Tests_File_Arc_TarFileTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_Arc_AllTests::main' )
	Tests_File_Arc_AllTests::main();
?>

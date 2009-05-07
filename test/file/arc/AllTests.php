<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_Arc_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once '../autoload.php5';
require_once 'file/arc/BzipTest.php';
require_once 'file/arc/GzipTest.php';
require_once 'file/arc/TarTest.php';
class File_Arc_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Arc' );
		$suite->addTestSuite('File_Arc_BzipTest'); 
		$suite->addTestSuite('File_Arc_GzipTest'); 
		$suite->addTestSuite('File_Arc_TarTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_Arc_AllTests::main' )
	File_Arc_AllTests::main();
?>

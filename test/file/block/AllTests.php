<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_Block_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'file/block/ReaderTest.php' );
require_once( 'file/block/WriterTest.php' );
class File_Block_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Block' );
		$suite->addTestSuite('File_Block_ReaderTest'); 
		$suite->addTestSuite('File_Block_WriterTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_Block_AllTests::main' )
	File_Block_AllTests::main();
?>

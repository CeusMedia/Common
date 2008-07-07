<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_Block_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/blocktLoaders.php5' );
require_once( 'Tests/file/block/ReaderTest.php' );
require_once( 'Tests/file/block/WriterTest.php' );
class Tests_File_Block_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/Block' );
		$suite->addTestSuite('Tests_File_Block_ReaderTest'); 
		$suite->addTestSuite('Tests_File_Block_WriterTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_Block_AllTests::main' )
	Tests_File_Block_AllTests::main();
?>

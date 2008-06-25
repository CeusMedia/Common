<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_File_Yaml_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/initLoaders.php5' ;
require_once 'Tests/file/yaml/ReaderTest.php';
require_once 'Tests/file/yaml/WriterTest.php';
class Tests_File_Yaml_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/File/Yaml' );
		$suite->addTestSuite('Tests_File_Yaml_ReaderTest'); 
		$suite->addTestSuite('Tests_File_Yaml_WriterTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_File_Yaml_AllTests::main' )
	Tests_File_Yaml_AllTests::main();
?>

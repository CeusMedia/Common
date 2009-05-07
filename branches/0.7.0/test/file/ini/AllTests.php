<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'File_INI_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once '../autoload.php5';
require_once( 'file/ini/ReaderTest.php' );
require_once( 'file/ini/EditorTest.php' );
require_once( 'file/ini/SectionReaderTest.php' );
require_once( 'file/ini/SectionEditorTest.php' );
class File_INI_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/File/INI' );
		$suite->addTestSuite('File_INI_ReaderTest'); 
		$suite->addTestSuite('File_INI_EditorTest'); 
		$suite->addTestSuite('File_INI_SectionReaderTest'); 
		$suite->addTestSuite('File_INI_SectionEditorTest'); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'File_INI_AllTests::main' )
	File_INI_AllTests::main();
?>

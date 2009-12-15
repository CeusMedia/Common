<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Database_PDO_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once 'test/initLoaders.php5';
class Test_Database_PDO_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database/PDO' );
		$suite->addTestSuite( 'Test_Database_PDO_ConnectionTest' ); 
		$suite->addTestSuite( 'Test_Database_PDO_TableReaderTest' ); 
		$suite->addTestSuite( 'Test_Database_PDO_TableWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Database_PDO_AllTests::main' )
	Test_Database_PDO_AllTests::main();
?>

<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Test_Database_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once dirname( __FILE__ ).'/../initLoaders.php5';
class Test_Database_AllTests
{	
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database' );
		$suite->addTest( Test_Database_PDO_AllTests::suite() );
		$suite->addTest( Test_Database_mySQL_AllTests::suite() );
		$suite->addTestSuite( 'Test_Database_BaseConnectionTest' ); 
		$suite->addTestSuite( 'Test_Database_ResultTest' ); 
		$suite->addTestSuite( 'Test_Database_RowTest' ); 
		$suite->addTestSuite( 'Test_Database_StatementBuilderTest' ); 
		$suite->addTestSuite( 'Test_Database_StatementCollectionTest' ); 
		$suite->addTestSuite( 'Test_Database_TableReaderTest' ); 
		$suite->addTestSuite( 'Test_Database_TableWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Test_Databas_AllTests::main' )
	Test_Database_AllTests::main();
?>

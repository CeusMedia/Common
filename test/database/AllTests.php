<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Database_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'database/pdo/AllTests.php' );
require_once( 'database/mysql/AllTests.php' );
require_once( 'database/BaseConnectionTest.php' );
require_once( 'database/ResultTest.php' );
require_once( 'database/RowTest.php' );
require_once( 'database/StatementBuilderTest.php' );
require_once( 'database/StatementCollectionTest.php' );
require_once( 'database/TableReaderTest.php' );
require_once( 'database/TableWriterTest.php' );
class Database_AllTests
{	
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database' );
		$suite->addTest( Database_PDO_AllTests::suite() );
		$suite->addTest( Database_mySQL_AllTests::suite() );
		$suite->addTestSuite( 'Database_BaseConnectionTest' ); 
		$suite->addTestSuite( 'Database_ResultTest' ); 
		$suite->addTestSuite( 'Database_RowTest' ); 
		$suite->addTestSuite( 'Database_StatementBuilderTest' ); 
		$suite->addTestSuite( 'Database_StatementCollectionTest' ); 
		$suite->addTestSuite( 'Database_TableReaderTest' ); 
		$suite->addTestSuite( 'Database_TableWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Databas_AllTests::main' )
	Database_AllTests::main();
?>

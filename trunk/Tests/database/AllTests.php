<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Database_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/database/pdo/AllTests.php' );
require_once( 'Tests/database/mysql/AllTests.php' );
require_once( 'Tests/database/BaseConnectionTest.php' );
require_once( 'Tests/database/ResultTest.php' );
require_once( 'Tests/database/RowTest.php' );
require_once( 'Tests/database/StatementBuilderTest.php' );
require_once( 'Tests/database/StatementCollectionTest.php' );
require_once( 'Tests/database/TableReaderTest.php' );
require_once( 'Tests/database/TableWriterTest.php' );
class Tests_Database_AllTests
{	
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database' );
		$suite->addTest( Tests_Database_PDO_AllTests::suite() );
		$suite->addTest( Tests_Database_mySQL_AllTests::suite() );
		$suite->addTestSuite( 'Tests_Database_BaseConnectionTest' ); 
		$suite->addTestSuite( 'Tests_Database_ResultTest' ); 
		$suite->addTestSuite( 'Tests_Database_RowTest' ); 
		$suite->addTestSuite( 'Tests_Database_StatementBuilderTest' ); 
		$suite->addTestSuite( 'Tests_Database_StatementCollectionTest' ); 
		$suite->addTestSuite( 'Tests_Database_TableReaderTest' ); 
		$suite->addTestSuite( 'Tests_Database_TableWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Databas_AllTests::main' )
	Tests_Database_AllTests::main();
?>

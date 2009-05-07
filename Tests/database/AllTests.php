<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Database_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/Database/pdo/AllTests.php' );
require_once( 'Tests/Database/mysql/AllTests.php' );
require_once( 'Tests/Database/BaseConnectionTest.php' );
require_once( 'Tests/Database/ResultTest.php' );
require_once( 'Tests/Database/RowTest.php' );
require_once( 'Tests/Database/StatementBuilderTest.php' );
require_once( 'Tests/Database/StatementCollectionTest.php' );
require_once( 'Tests/Database/TableReaderTest.php' );
require_once( 'Tests/Database/TableWriterTest.php' );
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

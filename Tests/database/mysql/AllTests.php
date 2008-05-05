<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Database_mySQL_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/Database/mysql/ConnectionTest.php' );
require_once( 'Tests/Database/mysql/TransactionConnectionTest.php' );
require_once( 'Tests/Database/mysql/ResultTest.php' );
require_once( 'Tests/Database/mysql/RowTest.php' );
class Tests_Database_mySQL_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'ClassContainer/Database/mySQL' );
		$suite->addTestSuite( 'Tests_Database_mySQL_ConnectionTest' ); 
		$suite->addTestSuite( 'Tests_Database_mySQL_TransactionConnectionTest' ); 
		$suite->addTestSuite( 'Tests_Database_mySQL_ResultTest' ); 
		$suite->addTestSuite( 'Tests_Database_mySQL_RowTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Database_mySQL_AllTests::main' )
	Tests_Database_mySQL_AllTests::main();
?>

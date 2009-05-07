<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Database_PDO_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/database/pdo/ConnectionTest.php' );
require_once( 'Tests/database/pdo/TableReaderTest.php' );
require_once( 'Tests/database/pdo/TableWriterTest.php' );
class Tests_Database_PDO_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database/PDO' );
		$suite->addTestSuite( 'Tests_Database_PDO_ConnectionTest' ); 
		$suite->addTestSuite( 'Tests_Database_PDO_TableReaderTest' ); 
		$suite->addTestSuite( 'Tests_Database_PDO_TableWriterTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Database_PDO_AllTests::main' )
	Tests_Database_PDO_AllTests::main();
?>

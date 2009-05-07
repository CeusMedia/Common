<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Database_mySQL_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'database/mysql/ConnectionTest.php' );
class Database_mySQL_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Database/mySQL' );
		$suite->addTestSuite( 'Database_mySQL_ConnectionTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Database_mySQL_AllTests::main' )
	Database_mySQL_AllTests::main();
?>

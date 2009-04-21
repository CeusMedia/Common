<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Alg_Crypt_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'Tests/alg/crypt/PasswordStrengthTest.php' );
require_once( 'Tests/alg/crypt/Rot13Test.php' );
require_once( 'Tests/alg/crypt/CaesarTest.php' );
class Tests_Alg_Crypt_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Crypt' );
		$suite->addTestSuite( 'Tests_Alg_Crypt_PasswordStrengthTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Crypt_Rot13Test' ); 
		$suite->addTestSuite( 'Tests_Alg_Crypt_CaesarTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Alg_Crypt_AllTests::main' )
	Tests_Alg_Crypt_AllTests::main();
?>

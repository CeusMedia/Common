<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Alg_Crypt_AllTests::main' );

require_once( 'PHPUnit/Framework/TestSuite.php' );
require_once( 'PHPUnit/TextUI/TestRunner.php' );
require_once( 'alg/crypt/PasswordStrengthTest.php' );
require_once( 'alg/crypt/Rot13Test.php' );
require_once( 'alg/crypt/CaesarTest.php' );
class Alg_Crypt_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Crypt' );
		$suite->addTestSuite( 'Alg_Crypt_PasswordStrengthTest' ); 
		$suite->addTestSuite( 'Alg_Crypt_Rot13Test' ); 
		$suite->addTestSuite( 'Alg_Crypt_CaesarTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Alg_Crypt_AllTests::main' )
	Alg_Crypt_AllTests::main();
?>

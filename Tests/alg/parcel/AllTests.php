<?php
if( !defined( 'PHPUnit_MAIN_METHOD' ) )
	define( 'PHPUnit_MAIN_METHOD', 'Tests_Alg_Parcel_AllTests::main' );

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'Tests/alg/parcel/FactoryTest.php';
require_once 'Tests/alg/parcel/PackerTest.php';
require_once 'Tests/alg/parcel/PacketTest.php';
class Tests_Alg_Parcel_AllTests
{
	public static function main()
	{
		PHPUnit_TextUI_TestRunner::run( self::suite() );
	}

	public static function suite()
	{
		$suite = new PHPUnit_Framework_TestSuite( 'cmClasses/Alg/Parcel' );
		$suite->addTestSuite( 'Tests_Alg_Parcel_FactoryTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Parcel_PackerTest' ); 
		$suite->addTestSuite( 'Tests_Alg_Parcel_PacketTest' ); 
		return $suite;
	}
}
if( PHPUnit_MAIN_METHOD == 'Tests_Alg_Parcel_AllTests::main' )
	Tests_Alg_Parcel_AllTests::main();
?>

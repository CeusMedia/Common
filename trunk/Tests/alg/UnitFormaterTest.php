<?php
/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_UnitFormater
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
require_once 'PHPUnit/Framework/TestCase.php'; 
require_once 'Tests/initLoaders.php5' ;
import( 'de.ceus-media.alg.UnitFormater' );
/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.alg.validation
 *	@extends		PHPUnit_Framework_TestCase
 *	@uses			Alg_Validation_PredicateValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			21.02.2008
 *	@version		0.1
 */
class Tests_Alg_UnitFormaterTest extends PHPUnit_Framework_TestCase
{
	public function testFormatBytes()
	{
		$assertion	= "256 B";
		$creation	= Alg_UnitFormater::formatBytes( 256 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 KB";
		$creation	= Alg_UnitFormater::formatBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 KB";
		$creation	= Alg_UnitFormater::formatBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 KB";
		$creation	= Alg_UnitFormater::formatBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 MB";
		$creation	= Alg_UnitFormater::formatBytes( 1024 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatKiloBytes()
	{
		$assertion	= "256 B";
		$creation	= Alg_UnitFormater::formatKiloBytes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 KB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 0.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 KB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 1.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 KB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 1.5, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 MB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 MB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 MB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 GB";
		$creation	= Alg_UnitFormater::formatKiloBytes( 1024 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMegaBytes()
	{
		$assertion	= "128 KB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 0.125, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 KB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 MB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 0.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 MB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 1.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 MB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 1.5, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 GB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 GB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 GB";
		$creation	= Alg_UnitFormater::formatMegaBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMilliSeconds()
	{
		$assertion	= "1 µs";
		$creation	= Alg_UnitFormater::formatMilliSeconds( 0.001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 s";
		$creation	= Alg_UnitFormater::formatMilliSeconds( 500 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 s";
		$creation	= Alg_UnitFormater::formatMilliSeconds( 500, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 m";
		$creation	= Alg_UnitFormater::formatMilliSeconds( 60 * 1000 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatSeconds()
	{
		$assertion	= "1 µs";
		$creation	= Alg_UnitFormater::formatSeconds( 0.000001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "100 ms";
		$creation	= Alg_UnitFormater::formatSeconds( 0.1 );

		$this->assertEquals( $assertion, $creation );
		$assertion	= "12 s";
		$creation	= Alg_UnitFormater::formatSeconds( 12 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 m";
		$creation	= Alg_UnitFormater::formatSeconds( 120 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2.1 m";
		$creation	= Alg_UnitFormater::formatSeconds( 126 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= Alg_UnitFormater::formatSeconds( 3600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 h";
		$creation	= Alg_UnitFormater::formatSeconds( 5400, 10 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= Alg_UnitFormater::formatSeconds( 24 * 60 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= Alg_UnitFormater::formatSeconds( 1.4 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 d";
		$creation	= Alg_UnitFormater::formatSeconds( 1.5 * 24 * 60 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 d";
		$creation	= Alg_UnitFormater::formatSeconds( 1.5 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 a";
		$creation	= Alg_UnitFormater::formatSeconds( 200 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMinutes()
	{
		$assertion	= "6 ms";
		$creation	= Alg_UnitFormater::formatMinutes( 0.0001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "6 s";
		$creation	= Alg_UnitFormater::formatMinutes( 0.1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "15 s";
		$creation	= Alg_UnitFormater::formatMinutes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "12 m";
		$creation	= Alg_UnitFormater::formatMinutes( 12 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.667 h";
		$creation	= Alg_UnitFormater::formatMinutes( 40, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= Alg_UnitFormater::formatMinutes( 40, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= Alg_UnitFormater::formatMinutes( 61, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 h";
		$creation	= Alg_UnitFormater::formatMinutes( 120, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 h";
		$creation	= Alg_UnitFormater::formatMinutes( 120, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= Alg_UnitFormater::formatMinutes( 24 * 60  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= Alg_UnitFormater::formatMinutes( 1.4 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 d";
		$creation	= Alg_UnitFormater::formatMinutes( 1.5 * 24 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 d";
		$creation	= Alg_UnitFormater::formatMinutes( 1.5 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 a";
		$creation	= Alg_UnitFormater::formatMinutes( 200 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );
	}
}
?>
<?php
declare( strict_types = 1 );
/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */

namespace CeusMedia\Common\Test\Alg;

use CeusMedia\Common\Alg\UnitFormater;
use CeusMedia\Common\Test\BaseCase;

/**
 *	TestUnit of Unit Formater.
 *	@package		Tests.Alg.Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 */
class UnitFormaterTest extends BaseCase
{
	public function testFormatPixels()
	{
		$assertion	= "256 P";
		$creation	= UnitFormater::formatPixels( 256 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 KP";
		$creation	= UnitFormater::formatPixels( 500 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 KP";
		$creation	= UnitFormater::formatPixels( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 MP";
		$creation	= UnitFormater::formatPixels( 1024 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}


	public function testFormatBytes()
	{
		$assertion	= "256 B";
		$creation	= UnitFormater::formatBytes( 256 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 KB";
		$creation	= UnitFormater::formatBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 KB";
		$creation	= UnitFormater::formatBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 KB";
		$creation	= UnitFormater::formatBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 MB";
		$creation	= UnitFormater::formatBytes( 1024 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatKiloBytes()
	{
		$assertion	= "256 B";
		$creation	= UnitFormater::formatKiloBytes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 KB";
		$creation	= UnitFormater::formatKiloBytes( 0.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 KB";
		$creation	= UnitFormater::formatKiloBytes( 1.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 KB";
		$creation	= UnitFormater::formatKiloBytes( 1.5, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 MB";
		$creation	= UnitFormater::formatKiloBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 MB";
		$creation	= UnitFormater::formatKiloBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 MB";
		$creation	= UnitFormater::formatKiloBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 GB";
		$creation	= UnitFormater::formatKiloBytes( 1024 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMegaBytes()
	{
		$assertion	= "128 KB";
		$creation	= UnitFormater::formatMegaBytes( 0.125, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 KB";
		$creation	= UnitFormater::formatMegaBytes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 MB";
		$creation	= UnitFormater::formatMegaBytes( 0.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 MB";
		$creation	= UnitFormater::formatMegaBytes( 1.5 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 MB";
		$creation	= UnitFormater::formatMegaBytes( 1.5, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 GB";
		$creation	= UnitFormater::formatMegaBytes( 512 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 GB";
		$creation	= UnitFormater::formatMegaBytes( 512, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "256 GB";
		$creation	= UnitFormater::formatMegaBytes( 256 * 1024 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMilliSeconds()
	{
		$assertion	= "1 µs";
		$creation	= UnitFormater::formatMilliSeconds( 0.001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.5 s";
		$creation	= UnitFormater::formatMilliSeconds( 500 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 s";
		$creation	= UnitFormater::formatMilliSeconds( 500, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 m";
		$creation	= UnitFormater::formatMilliSeconds( 60 * 1000 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatSeconds()
	{
		$assertion	= "1 µs";
		$creation	= UnitFormater::formatSeconds( 0.000001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "100 ms";
		$creation	= UnitFormater::formatSeconds( 0.1 );

		$this->assertEquals( $assertion, $creation );
		$assertion	= "12 s";
		$creation	= UnitFormater::formatSeconds( 12 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 m";
		$creation	= UnitFormater::formatSeconds( 120 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2.1 m";
		$creation	= UnitFormater::formatSeconds( 126 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= UnitFormater::formatSeconds( 3600 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 h";
		$creation	= UnitFormater::formatSeconds( 5400, 10 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= UnitFormater::formatSeconds( 24 * 60 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= UnitFormater::formatSeconds( 1.4 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 d";
		$creation	= UnitFormater::formatSeconds( 1.5 * 24 * 60 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 d";
		$creation	= UnitFormater::formatSeconds( 1.5 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 a";
		$creation	= UnitFormater::formatSeconds( 200 * 24 * 60 * 60, 0 );
		$this->assertEquals( $assertion, $creation );
	}

	public function testFormatMinutes()
	{
		$assertion	= "6 ms";
		$creation	= UnitFormater::formatMinutes( 0.0001 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "6 s";
		$creation	= UnitFormater::formatMinutes( 0.1 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "15 s";
		$creation	= UnitFormater::formatMinutes( 0.25 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "12 m";
		$creation	= UnitFormater::formatMinutes( 12 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "0.667 h";
		$creation	= UnitFormater::formatMinutes( 40, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= UnitFormater::formatMinutes( 40, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 h";
		$creation	= UnitFormater::formatMinutes( 61, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 h";
		$creation	= UnitFormater::formatMinutes( 120, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 h";
		$creation	= UnitFormater::formatMinutes( 120, 3 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= UnitFormater::formatMinutes( 24 * 60  );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 d";
		$creation	= UnitFormater::formatMinutes( 1.4 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1.5 d";
		$creation	= UnitFormater::formatMinutes( 1.5 * 24 * 60 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "2 d";
		$creation	= UnitFormater::formatMinutes( 1.5 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );

		$assertion	= "1 a";
		$creation	= UnitFormater::formatMinutes( 200 * 24 * 60, 0 );
		$this->assertEquals( $assertion, $creation );
	}
}

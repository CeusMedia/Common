<?php
/**
 *	Converter for different Formats of Colors.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			23.09.2005
 *	@version		0.6
 */
/**
 *	Converter for Colors.
 *	@category		cmClasses
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			23.09.2005
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Alg_ColorConverter
{
	/**
	 *	Converts CMY to CMYK.
	 *	@access		public
	 *	@static
	 *	@param		array	cmy		CMY-Color as array
	 *	@return		array
	 */
	public static function cmy2cmyk( $cmy )
	{
		list( $c, $m, $y ) = $cmy;
		$k	= min( $c, $m, $y );
		$c	= ( $c - $k ) / ( 1 - $k );
		$m	= ( $m - $k ) / ( 1 - $k );
		$y	= ( $y - $k ) / ( 1 - $k );
		return array( $c, $m, $y, $k );
	}

	/**
	 *	Converts CMYK to CMY.
	 *	@access		public
	 *	@static
	 *	@param		array	cmyk	CMYK-Color as array
	 *	@return		array
	 */
	public static function cmyk2cmy( $cmyk )
	{
		list( $c, $m, $y, $k ) = $cmyk;
		$c	= min( 1, $c * ( 1 - $k ) + $k );
		$m	= min( 1, $m * ( 1 - $k ) + $k );
		$y	= min( 1, $y * ( 1 - $k ) + $k );
		return array( $c, $m, $y );
	}

	/**
	 *	Converts CMY to RGB.
	 *	@access		public
	 *	@static
	 *	@param		array	cmy		CMY-Color as array
	 *	@return		array
	 */
	public static function cmy2rgb( $cmy )
	{
		list( $c, $m, $y ) = $cmy;
		$r	= 255 * ( 1 - $c );
		$g	= 255 * ( 1 - $m );
		$b	= 255 * ( 1 - $y );
		return array( $r, $g, $b );
	}

	/**
	 *	Converts CMYK to RGB.
	 *	@access		public
	 *	@static
	 *	@param		array	cmyk	CMKY-Color as array
	 *	@return		array
	 */
	public static function cmyk2rgb( $cmyk )
	{
		return self::cmy2rgb( self::cmyk2cmy( $cmyk ) );
	}

	/**
	 *	Converts HSV to HTML.
	 *	@access		public
	 *	@static
	 *	@param		array	hsv		HSV-Color as array
	 *	@return		string
	 */
	public static function hsv2html( $hsv )
	{
		return self::rgb2html( self::hsv2rgb( $hsv ) );
	}		

	/**
	 *	Converts HSV to RGB.
	 *	@access		public
	 *	@static
	 *	@param		array	hsv		HSV-Color as array
	 *	@return		array
	 */
	public static function hsv2rgb( $hsv )
	{
		list( $h, $s, $v ) = $hsv;
		$rgb = array();
		$h	= $h / 60;
		$s	= $s / 100;
		$v	= $v / 100;
		if( $s == 0 )
		{
			$rgb[0]	= $v * 255;
			$rgb[1]	= $v * 255;
			$rgb[2]	= $v * 255;
		}
		else
		{
			$rgb_dec = array();
			$i	= floor( $h );
			$p	= $v * ( 1 - $s );
			$q	= $v * ( 1 - $s * ( $h - $i ) );
			$t	= $v * ( 1 - $s * ( 1 - ( $h - $i ) ) );
			switch( $i )
			{
				case 0:
				$rgb_dec[0]	= $v;
				$rgb_dec[1]	= $t;
				$rgb_dec[2]	= $p;
				break;
				case 1:
				$rgb_dec[0]	= $q;
				$rgb_dec[1]	= $v;
				$rgb_dec[2]	= $p;
				break;
				case 2:
				$rgb_dec[0]	= $p;
				$rgb_dec[1]	= $v;
				$rgb_dec[2]	= $t;
				break;
				case 3:
				$rgb_dec[0]	= $p;
				$rgb_dec[1]	= $q;
				$rgb_dec[2]	= $v;
				break;
				case 4:
				$rgb_dec[0]	= $t;
				$rgb_dec[1]	= $p;
				$rgb_dec[2]	= $v;
				break;
				case 5:
				$rgb_dec[0]	= $v;
				$rgb_dec[1]	= $p;
				$rgb_dec[2]	= $q;
				break;
				case 6:
				$rgb_dec[0]	= $v;
				$rgb_dec[1]	= $p;
				$rgb_dec[2]	= $q;
				break;
			}
			$rgb[0]	= round( $rgb_dec[0] * 255 );
			$rgb[1]	= round( $rgb_dec[1] * 255 );
			$rgb[2]	= round( $rgb_dec[2] * 255 );
		}
		return $rgb;
	}
	
	/**
	 *	Converts HTML to RGB.
	 *	@access		public
	 *	@static
	 *	@param		string	html		HTML-Color as string
	 *	@return		array
	 */
	public static function html2rgb( $string )
	{
		sscanf( $string, "%2X%2X%2X", $r, $g, $b );
		return array( $r, $g, $b );
	}
	
	/**
	 *	Converts HTML to hsv.
	 *	@access		public
	 *	@static
	 *	@param		string	html		HTML-Color as string
	 *	@return		array
	 */
	public static function html2hsv( $string )
	{
		sscanf( $string, "%2X%2X%2X", $r, $g, $b );
		return self::rgb2hsv( array( $r, $g, $b ) );
	}
	
	/**
	 *	Converts RGB to CMY.
	 *	@access		public
	 *	@static
	 *	@param		array	rgb		RGB-Color as array
	 *	@return		array
	 */
	public static function rgb2cmy( $rgb )
	{
		list( $r, $g, $b ) = $rgb;
		$c	= 1 - ( $r / 255 );
		$m	= 1 - ( $g / 255 );
		$y	= 1 - ( $b / 255 );
		return array( $c, $m, $y );
	}

	/**
	 *	Converts RGB to CMYK.
	 *	@access		public
	 *	@static
	 *	@param		array	rgb		RGB-Color as array
	 *	@return		array
	 */
	public static function rgb2cmyk( $rgb )
	{
		return self::cmy2cmyk( self::rgb2cmy( $rgb ) );
	}

	/**
	 *	Converts RGB to HSV.
	 *	@access		public
	 *	@static
	 *	@param		array	rgb		RGB-Color as array
	 *	@return		array
	 */
	public static function rgb2hsv( $rgb )
	{
#		return self::rgb2hsv_2( $rgb );
		list( $r, $g, $b ) = $rgb;
		$v	= max( $r, $g, $b );
		$t	= min( $r, $g, $b );
		$s	= ( $v == 0 ) ? 0 : ( $v - $t ) / $v;
		if( $s == 0 )
			$h	= 0;
		else
		{
			$a	= $v - $t;
			$cr	= ( $v - $r ) / $a;
			$cg	= ( $v - $g ) / $a;
			$cb	= ( $v - $b ) / $a;
			$h	= ( $r == $v ) ? $cb - $cg : ( ( $g == $v ) ? 2 + $cr - $cb : ( ( $b == $v ) ? $h = 4 + $cg - $cr : 0 ) );
			$h	= 60 * $h;
			$h	= ( $h < 0 ) ? $h + 360 : $h;
		}
		return array( round( $h ), round( $s * 100 ), round( $v / 2.55 ) );
	}

	private function rgb2hsv_2( $rgb )
	{
		list( $r, $g, $b ) = $rgb;
		$r		= $r / 255.0;
		$g		= $g / 255.0;
		$b		= $b / 255.0;
		$min	= min( min( $r, $g ), $b );
		$max	= max( max( $r, $g ), $b );
		$delta	= $max - $min;
		$v		= $max;
	
		if( $delta == 0 )
		{
			$h = 0;
			$s = 0;
		}
		else
		{
			$s = $delta / $max;
			$dR = ( ( ( $max - $r ) / 6 ) + ( $delta / 2 ) ) / $delta;
			$dG = ( ( ( $max - $g ) / 6 ) + ( $delta / 2 ) ) / $delta;
			$dB = ( ( ( $max - $b ) / 6 ) + ( $delta / 2 ) ) / $delta;
			if( $r == $max )
				$h = $dB - $dG;
			else if( $g == $max )
				$h = ( 1 / 3 ) + $dR - $dB;
			else
				$h = ( 2 / 3 ) + $dG - $dR;
			if( $h < 0 )
				$h += 1;
			if( $h > 1 )
				$h -= 1;
		}
		return array(
			round( $h * 360 ),
			round( $s * 100 ),
			round( $v * 100 )
		);
	}

	/**
	 *	Converts RGB to HTML.
	 *	@access		public
	 *	@static
	 *	@param		array	rgb		RGB-Color as array
	 *	@return		string
	 */
	public static function rgb2html( $rgb )
	{
		list( $r, $g, $b ) = $rgb;
		$html	= sprintf( "%2X%2X%2X", $r, $g, $b );
		$html	= str_replace( " ", "0", $html );
		return	$html; 
	}
	
	/**
	 *	Converts RGB to XYZ.
	 *	@access		public
	 *	@static
	 *	@param		array	rgb		RGB-Color as array
	 *	@return		array
	 */
	public static function rgb2xyz( $rgb )
	{
		list( $r, $g, $b ) = $rgb;
		$r	= $r / 255;
		$g	= $g / 255;
		$b	= $b / 255;
		$x	= 0.430574 * $r + 0.341550 * $g + 0.178325 * $b;
		$y	= 0.222015 * $r + 0.706655 * $g + 0.071330 * $b;
		$z	= 0.020183 * $r + 0.129553 * $g + 0.939180 * $b;
		return array( $x, $y, $z );
	}
	
	/**
	 *	Converts XYZ to RGB.
	 *	@access		public
	 *	@static
	 *	@param		array	xyz		XYZ-Color as array
	 *	@return		array
	 */
	public static function xyz2rgb( $xyz )
	{
		list( $x, $y, $z ) = $xyz;
		$r	= 3.063219 * $x - 1.393326 * $y - 0.475801 * $z;
		$g	= -0.969245 * $x + 1.875968 * $y + 0.041555 * $z;
		$b	= 0.067872 * $x - 0.228833 * $y + 1.069251 * $z;
		$r	= round( $r * 255 );
		$g	= round( $g * 255 );
		$b	= round( $b * 255 );
		return array( $r, $g, $b );
	}
	
#	/**
#	 *	Converts XYZ to LUV.
#	 *	@access		public
#	 *	@param		array	xyz		XYZ-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function xyz2luv( $xyz )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $x, $y, $z ) = $xyz;
#		return array( $l, $u, $v );
#	}
#	
#	/**
#	 *	Converts LUV to XYZ.
#	 *	@access		public
#	 *	@param		array	luv		LUV-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function luv2xyz( $luv )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $l, $u, $v ) = $luv;
#		return array( $x, $y, $z );
#	}
#	
#	/**
#	 *	Converts XYZ to LAB.
#	 *	@access		public
#	 *	@param		array	xyz		XYZ-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function xyz2lab( $xyz )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $x, $y, $z ) = $xyz;
#		return array( $l, $a, $b );
#	}
#	
#	/**
#	 *	Converts LAB to XYZ.
#	 *	@access		public
#	 *	@param		array	lab		LAB-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function lab2xyz( $lab )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $l, $a, $b ) = $lab;
#		return array( $x, $y, $z );
#	}
#
#	/**
#	 *	Converts LAB to LUV.
#	 *	@access		public
#	 *	@param		array	lab		LAB-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function lab2luv( $lab )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $l, $a, $b ) = $lab;
#		return array( $l, $u, $v );
#	}
#	
#	/**
#	 *	Converts LUV to LAB.
#	 *	@access		public
#	 *	@param		array	luv		LUV-Color as array
#	 *	@return		array
#	 *	@author		Christian Würker <christian.wuerker@ceus-media.de>
#	 *	@since		23.09.2005
#	 *	@version		0.1
#	 */
#	public function luv2lab( $luv )
#	{
#		trigger_error( "Not implemented yet", E_USER_ERROR );
#		list( $l, $u, $v ) = $luv;
#		return array( $l, $a, $b );
#	}
}
?>
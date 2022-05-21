<?php
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			22.04.2008
 */
namespace CeusMedia\Common\Alg;

define( 'SIZE_BYTE', pow( 1024, 0 ) );
define( 'SIZE_KILOBYTE', pow( 1024, 1 ) );
define( 'SIZE_MEGABYTE', pow( 1024, 2 ) );
define( 'SIZE_GIGABYTE', pow( 1024, 3 ) );
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			18.10.2007
 */
class UnitFormater
{
	/**	@var		array		$unitBytes		List of Byte Units */
	public static $unitBytes	= array(
		'B',
		'KB',
		'MB',
		'GB',
		'TB',
		'PB',
		'EB',
		'ZB',
		'YB'
	);

	/**	@var		array		$unitPixels		List of Pixel Units */
	public static $unitPixels	= array(
		'P',
		'KP',
		'MP',
		'GP',
		'TP',
		'PP',
		'EP',
		'ZP',
		'YP'
	);

	/**	@var		array		$unitBytes		List of Second Units */
	public static $unitSeconds	= array(
		'µs',
		'ms',
		's',
		'm',
		'h',
		'd',
		'a'
	);

	/**
	 *	Formats Number of Bytes by switching to next higher Unit if an set Edge is reached.
	 *	Edge is a Factor when to switch to ne next higher Unit, eG. 0.5 means 50% of 1024.
	 *	If you enter 512 (B) it will return 0.5 KB.
	 *	Caution! With Precision at 0 you may have Errors from rounding.
	 *	To avoid the Units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatBytes( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		//  step to first Unit
		$unitKey	= 0;
		//  1024 Bytes are 1 Kilo Byte
		$divider	= 1024;
		//  avoid negative Edges
		$edge		= abs( $edge );
		//  avoid senseless Edges
		$edge		= $edge > 1 ? 1 : $edge;
		//  calculate Edge Value
		$edgeValue	= $divider * $edge;
		//  Value is larger than Edge
		while( $float >= $edgeValue )
		{
			//  step to next Unit
			$unitKey ++;
			//  calculate Value in new Unit
			$float	/= $divider;
		}
		//  Precision is set
		if( is_int( $precision ) )
			//  round Value
			$float	= round( $float, $precision );
		//  Indention is set
		if( is_string( $indent ) )
			//  append Unit
			$float	= $float.$indent.self::$unitBytes[$unitKey];
		//  return resultung Value
		return $float;
	}

	/**
	 *	Formats Kilo Bytes like formatBytes.
	 *	You can also enter 0.25 (KB) and it will return 256 B.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Kilo Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatKiloBytes( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatBytes( $float * 1024, $precision, $indent, $edge );
	}

	/**
	 *	Formats Mega Bytes like formatBytes.
	 *	You can also enter 0.25 (MB) and it will return 256 KB.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Mega Bytes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMegaBytes( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatBytes( $float * 1024 * 1024, $precision, $indent, $edge );
	}

	/**
	 *	Formats Micro Seconds by switching to next higher Unit if an set Edge is reached.
	 *	Edge is a Factor when to switch to ne next higher Unit, eG. 0.5 means 50% of 1000.
	 *	If you enter 500 (µs) it will return 0.5 ms.
	 *	Caution! With Precision at 0 you may have Errors from rounding.
	 *	To avoid the Units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Micro Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMicroSeconds( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		//  step to first Unit
		$unitKey	= 0;
		//  1000 Micro Seconds are 1 Milli Second
		$divider	= 1000;
		//  avoid negative Edges
		$edge		= abs( $edge );
		//  avoid senseless Edges
		$edge		= $edge > 1 ? 1 : $edge;
		//  calculate Edge Value
		$edgeValue	= $divider * $edge;

		//  Value is larger than Edge
		while( $float >= $edgeValue )
		{
			//  step to next Unit
			$unitKey ++;
			//  calculate Value in new Unit
			$float	/= $divider;
			//  Seconds are reached
			if( $unitKey == 2 )
			{
				//  60 Seconds per Minute
				$divider	= 60;
				//  calculate new Edge
				$edgeValue	= $edge * $divider;
			}
			//  Hours are reached
			if( $unitKey == 4 )
			{
				//  24 Hours per Day
				$divider	= 24;
				//  calculate new Edge
				$edgeValue	= $edge * $divider;
			}
			//  Days are reached
			if( $unitKey == 5 )
			{
				//  365 Days per Year
				$divider	= 365;
				//  calculate new Edge
				$edgeValue	= $edge * $divider;
			}
		}
		//  Precision is set
		if( is_int( $precision ) )
			//  round Value
			$float	= round( $float, $precision );
		//  Indention is set
		if( is_string( $indent ) )
			//  append Unit
			$float	= $float.$indent.self::$unitSeconds[$unitKey];
		//  return resulting Value
		return $float;
	}

	/**
	 *	Formats Milli Seconds like formatMicroSeconds.
	 *	You can also enter 0.1 (ms) and it will return 100 µs.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Milli Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMilliSeconds( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 1000, $precision, $indent, $edge );
	}

	/**
	 *	Formats Minutes like formatMicroSeconds.
	 *	You can also enter 0.1 (m) and it will return 6 s.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Minutes
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatMinutes( $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 60000000, $precision, $indent, $edge );
	}

	/**
	 *	Formats Number.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number to format
	 *	@param		int			$unit			Number of Digits for dot to move to left
	 *	@param		int			$precision		Number of Digits after dot
	 *	@return		void
	 *	@deprecated	uncomplete method, please remove
	 */
	public static function formatNumber( $float, int $unit = 1, int $precision = 0 ): string
	{
		Deprecation::getInstance()->setExceptionVersion( '0.8' )
			->message(  'Use one of the other methods instead' );
		if( (int) $unit )
		{
			$float	= $float / $unit;
			if( is_int( $precision ) )
				$float	= round( $float, $precision );
		}
		return $float;
	}

	/**
	 *	Formats Number of Pixels by switching to next higher Unit if an set Edge is reached.
	 *	Edge is a Factor when to switch to ne next higher Unit, eG. 0.5 means 50% of 1000.
	 *	If you enter 500 (P) it will return 0.5 KP.
	 *	Caution! With Precision at 0 you may have Errors from rounding.
	 *	To avoid the Units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float		$number			Number of Pixels
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatPixels( $number, int $precision = 1, string $indent = ' ', $edge = 0.5 ): string
	{
		//  step to first Unit
		$unitKey	= 0;
		//  1000 Pixels are 1 Kilo Pixel
		$divider	= 1000;
		//  avoid negative Edges
		$edge		= abs( $edge );
		//  avoid senseless Edges
		$edge		= $edge > 1 ? 1 : $edge;
		//  calculate Edge Value
		$edgeValue	= $divider * $edge;
		//  Value is larger than Edge
		while( $number >= $edgeValue )
		{
			//  step to next Unit
			$unitKey ++;
			//  calculate Value in new Unit
			$number	/= $divider;
		}
		//  Precision is set
		if( is_int( $precision ) )
			//  round Value
			$number	= round( $number, $precision );
		//  Indention is set
		if( is_string( $indent ) )
			//  append Unit
			$number	= $number.$indent.self::$unitPixels[$unitKey];
		//  return resultung Value
		return $number;
	}

	/**
	 *	Formats Seconds like formatMicroSeconds.
	 *	You can also enter 0.1 (s) and it will return 100 ms.
	 *	@access		public
	 *	@static
	 *	@param		float		$float			Number of Seconds
	 *	@param		int			$precision		Number of Floating Point Digits
	 *	@param		string		$indent			Space between Number and Unit
	 *	@param		float		$edge			Factor of next higher Unit when to break
	 *	@return		string
	 */
	public static function formatSeconds( $float, int $precision = 1, string $indent = ' ', $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 1000000, $precision, $indent, $edge );
	}
}

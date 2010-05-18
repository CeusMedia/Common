<?php
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			22.04.2008
 *	@version		$Id$
 */
define( 'SIZE_BYTE', pow( 1024, 0 ) );
define( 'SIZE_KILOBYTE', pow( 1024, 1 ) );
define( 'SIZE_MEGABYTE', pow( 1024, 2 ) );
define( 'SIZE_GIGABYTE', pow( 1024, 3 ) );
/**
 *	Formats Numbers intelligently and adds Units to Bytes and Seconds.
 *	@category		cmClasses
 *	@package		Alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.10.2007
 *	@version		$Id$
 */
class Alg_UnitFormater
{
	/**	@var		array		$unitBytes		List of Byte Units */
	public static $unitBytes	= array(
		"B",
		"KB",
		"MB",
		"GB",
	);
	
	/**	@var		array		$unitBytes		List of Second Units */
	public static $unitSeconds	= array(
		"µs",
		"ms",
		"s",
		"m",
		"h",
		"d",
		"a"
	);

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
	public static function formatNumber( $float, $unit = 1, $precision = 0 )
	{
		if( (int) $unit )
		{
			$float	= $float / $unit;
			if( is_int( $precision ) )
				$float	= round( $float, $precision );
		}
		return $float;
	}
	
	/**
	 *	Formats Number of Bytes Seconds by switching to next higher Unit if an set Edge is reached.
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
	public static function formatBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		$unitKey	= 0;															//  step to first Unit
		$divider	= 1024;															//  1024 Bytes are 1 Kilo Byte
		$edge		= abs( $edge );													//  avoid negative Edges
		$edge		= $edge > 1 ? 1 : $edge;										//  avoid senseless Edges
		$edgeValue	= $divider * $edge;												//  calculate Edge Value
		while( $float >= $edgeValue )												//  Value is larger than Edge
		{
			$unitKey ++;															//  step to next Unit
			$float	/= $divider;													//  calculate Value in new Unit
		}
		if( is_int( $precision ) )													//  Precision is set
			$float	= round( $float, $precision );									//  round Value
		if( is_string( $indent ) )													//  Indention is set
			$float	= $float.$indent.self::$unitBytes[$unitKey];					//  append Unit
		return $float;																//  return resultung Value
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
	public static function formatKiloBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
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
	public static function formatMegaBytes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
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
	public static function formatMicroSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		$unitKey	= 0;															//  step to first Unit
		$divider	= 1000;															//  1000 Micro Seconds are 1 Milli Second
		$edge		= abs( $edge );													//  avoid negative Edges											
		$edge		= $edge > 1 ? 1 : $edge;										//  avoid senseless Edges
		$edgeValue	= $divider * $edge;												//  calculate Edge Value

		while( $float >= $edgeValue )												//  Value is larger than Edge
		{
			$unitKey ++;															//  step to next Unit
			$float	/= $divider;													//  calculate Value in new Unit
			if( $unitKey == 2 )														//  Seconds are reached
			{
				$divider	= 60;													//  60 Seconds per Minute
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
			if( $unitKey == 4 )														//  Hours are reached
			{
				$divider	= 24;													//  24 Hours per Day
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
			if( $unitKey == 5 )														//  Days are reached
			{
				$divider	= 365;													//  365 Days per Year
				$edgeValue	= $edge * $divider;										//  calculate new Edge
			}
		}
		if( is_int( $precision ) )													//  Precision is set
			$float	= round( $float, $precision );									//  round Value
		if( is_string( $indent ) )													//  Indention is set
			$float	= $float.$indent.self::$unitSeconds[$unitKey];					//  append Unit
		return $float;																//  return resulting Value
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
	public static function formatMilliSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
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
	public static function formatMinutes( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatMicroSeconds( $float * 60000000, $precision, $indent, $edge );
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
	public static function formatSeconds( $float, $precision = 1, $indent = " ", $edge = 0.5 )
	{
		return self::formatMicroSeconds( $float * 1000000, $precision, $indent, $edge );
	}
}
?>
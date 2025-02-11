<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Formats numbers intelligently and adds units to bytes and seconds.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg;

define( 'SIZE_BYTE', 1024 ** 0 );
define( 'SIZE_KILOBYTE', 1024 ** 1 );
define( 'SIZE_MEGABYTE', 1024 ** 2 );
define( 'SIZE_GIGABYTE', 1024 ** 3 );

/**
 *	Formats numbers intelligently and adds units to bytes and seconds.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class UnitFormater
{
	/**	@var		array		$unitBytes		List of byte units */
	public static array $unitBytes	= [
		'B',
		'KB',
		'MB',
		'GB',
		'TB',
		'PB',
		'EB',
		'ZB',
		'YB',
	];

	/**	@var		array		$unitPixels		List of pixel units */
	public static array $unitPixels	= [
		'P',
		'KP',
		'MP',
		'GP',
		'TP',
		'PP',
		'EP',
		'ZP',
		'YP',
	];

	/**	@var		array<string>		$unitBytes		List of second units */
	public static array $unitSeconds	= [
		'µs',
		'ms',
		's',
		'm',
		'h',
		'd',
		'a',
	];

	/**
	 *	Formats number of bytes by switching to next higher unit if a set edge is reached.
	 *	Edge is a factor when to switch to next higher unit, eG. 0.5 means 50% of 1024.
	 *	If you enter 512 (B) it will return 0.5 KB.
	 *	Caution! With precision at 0 you may have errors from rounding.
	 *	To avoid the units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float|int	$float			Number of bytes
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatBytes( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		//  step to first unit
		$unitKey	= 0;
		//  1024 bytes are 1 kilo byte
		$divider	= 1024;
		//  avoid negative edges
		$edge		= abs( $edge );
		//  avoid senseless edges
		$edge		= min( $edge, 1 );
		//  calculate edge value
		$edgeValue	= $divider * $edge;
		//  value is larger than edge
		while( $float >= $edgeValue )
		{
			//  step to next unit
			$unitKey ++;
			//  calculate value in new unit
			$float	/= $divider;
		}
		//  round value
		$float	= round( $float, $precision );
		//  append unit and return
		return $float.$indent.self::$unitBytes[$unitKey];
	}

	/**
	 *	Formats kilobytes like formatBytes.
	 *	You can also enter 0.25 (KB) and it will return 256 B.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of kilobytes
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatKiloBytes( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatBytes( $float * 1024, $precision, $indent, $edge );
	}

	/**
	 *	Formats megabytes like formatBytes.
	 *	You can also enter 0.25 (MB) and it will return 256 KB.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of megabytes
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatMegaBytes( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatBytes( $float * 1024 * 1024, $precision, $indent, $edge );
	}

	/**
	 *	Formats microseconds by switching to next higher unit if a set edge is reached.
	 *	Edge is a factor when to switch to next higher unit, eG. 0.5 means 50% of 1000.
	 *	If you enter 500 (µs) it will return 0.5 ms.
	 *	Caution! With precision at 0 you may have errors from rounding.
	 *	To avoid the units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of microseconds
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatMicroSeconds( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		//  step to first unit
		$unitKey	= 0;
		//  1000 microseconds are 1 millisecond
		$divider	= 1000;
		//  avoid negative edges
		$edge		= abs( $edge );
		//  avoid senseless edges
		$edge		= min( $edge, 1 );
		//  calculate edge value
		$edgeValue	= $divider * $edge;

		//  Value is larger than edge
		while( $float >= $edgeValue )
		{
			//  step to next unit
			$unitKey ++;
			//  calculate value in new unit
			$float	/= $divider;
			//  seconds are reached
			if( $unitKey == 2 )
			{
				//  60 seconds per minute
				$divider	= 60;
				//  calculate new edge
				$edgeValue	= $edge * $divider;
			}
			//  hours are reached
			if( $unitKey == 4 )
			{
				//  24 hours per day
				$divider	= 24;
				//  calculate new edge
				$edgeValue	= $edge * $divider;
			}
			//  days are reached
			if( $unitKey == 5 )
			{
				//  365 days per Year
				$divider	= 365;
				//  calculate new edge
				$edgeValue	= $edge * $divider;
			}
		}
		//  precision is set
		$float	= round( $float, $precision );
		//  append unit and return
		return $float.$indent.self::$unitSeconds[$unitKey];
	}

	/**
	 *	Formats milliseconds like formatMicroSeconds.
	 *	You can also enter 0.1 (ms) and it will return 100 µs.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of milliseconds
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatMilliSeconds( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 1000, $precision, $indent, $edge );
	}

	/**
	 *	Formats minutes like formatMicroSeconds.
	 *	You can also enter 0.1 (m) and it will return 6 s.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of minutes
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatMinutes( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 60_000_000, $precision, $indent, $edge );
	}

	/**
	 *	Formats number of pixels by switching to next higher unit if a set edge is reached.
	 *	Edge is a factor when to switch to next higher unit, eG. 0.5 means 50% of 1000.
	 *	If you enter 500 (P) it will return 0.5 KP.
	 *	Caution! With precision at 0 you may have errors from rounding.
	 *	To avoid the units to be appended, enter FALSE or NULL for indent.
	 *	@access		public
	 *	@static
	 *	@param		float		$number			Number of pixels
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatPixels( float $number, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		//  step to first unit
		$unitKey	= 0;
		//  1000 pixels are 1 kilo pixel
		$divider	= 1000;
		//  avoid negative edges
		$edge		= abs( $edge );
		//  avoid senseless edges
		$edge		= min( $edge, 1 );
		//  calculate edge value
		$edgeValue	= $divider * $edge;
		//  value is larger than edge
		while( $number >= $edgeValue )
		{
			//  step to next unit
			$unitKey ++;
			//  calculate value in new unit
			$number	/= $divider;
		}
		//  round value
		$number	= round( $number, $precision );
		//  append unit and return
		return $number.$indent.self::$unitPixels[$unitKey];
	}

	/**
	 *	Formats Seconds like formatMicroSeconds.
	 *	You can also enter 0.1 (s) and it will return 100 ms.
	 *	@access		public
	 *	@static
	 *	@param		float|int 	$float			Number of seconds
	 *	@param		int			$precision		Number of floating point digits
	 *	@param		string		$indent			Space between number and unit
	 *	@param		float		$edge			Factor of next higher unit when to break
	 *	@return		string
	 */
	public static function formatSeconds( float|int $float, int $precision = 1, string $indent = ' ', float $edge = 0.5 ): string
	{
		return self::formatMicroSeconds( $float * 1_000_000, $precision, $indent, $edge );
	}
}

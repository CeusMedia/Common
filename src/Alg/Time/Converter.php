<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Converting Unix Timestamps to Human Time in different formats and backwards.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Time;

use InvalidArgumentException;

/**
 *	Converting Unix Timestamps to Human Time in different formats and backwards.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Time
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			revise, internationalise
 */
class Converter
{

	/**
	 *	Complements Month Date Format for Time Predicates with Month Start or Month End for Formats.
	 *	Allowed Formats are: m.y, m.Y, m/y, m/Y, y-m, Y-m
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be complemented
	 *	@param		int			$mode		Complement Mode (0:Month Start, 1:Month End)
	 *	@return		string
	 */
	public static function complementMonthDate( string $string, int $mode = 0 ): string
	{
		$string	= trim( $string );
		if( preg_match( "@^\d{1,2}\.(\d{2}){1,2}$@", $string ) ){
			$string	= "01.".$string;
		}
		else if( preg_match( "@^(\d{2}){1,2}-\d{1,2}$@", $string ) ){
			$string	.= "-01";
		}
		else if( preg_match( "@^\d{1,2}/(\d{2}){1,2}$@", $string ) ){
			$pos	= strpos( $string, "/" );
			$string	= substr( $string, 0, $pos )."/01".substr( $string, $pos );
		}
		else
			return $string;
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been complemented.' );

		$string		= date( "c", $time );
		if( $mode ){
			$string		= date( "c", $time + 24 * 60 * 60 -1 );
			$complement	= date( "t", $time );
			$string		= str_replace( "-01T", "-".$complement."T", $string );
		}
		return $string;
	}

	public static function convertTimeToHuman( int $seconds ): string
	{
		$_min	= 60;
		$_hour	= 60 * $_min;
		$_day	= 24 * $_hour;
		$_year	= 365.25 * $_day;

		$years	= floor( $seconds / $_year );
		$seconds	= $seconds - $years * $_year;
		$days	= floor( $seconds / $_day );
		$seconds	= $seconds - $days * $_day;
		$hours	= floor( $seconds / $_hour );
		$seconds	= $seconds - $hours * $_hour;
		$mins	= floor( $seconds / $_min );
		$seconds	= $seconds - $mins * $_min;

		return $years."a ".$days."d ".$hours."h ".$mins."m ".$seconds."s";
	}

	/**
	 *	Converts Unix Timestamp to a human time format.
	 *	@access		public
	 *	@static
	 *	@param		integer|string	$timestamp		Unix Timestamp
	 *	@param		string			$format			Format of human time (date|monthdate|datetime|duration|custom format)
	 *	@return		string|NULL
	 */
	public static function convertToHuman( $timestamp, string $format ): ?string
	{
		$timestamp	= (int) $timestamp;
		$human = "";
		if( $format == "date" )
			$human = date( "d.m.Y", (int) $timestamp );
		else if( $format == "monthdate" )
			$human = date( "m.Y", (int) $timestamp );
		else if( $format == "time" )
			$human = date( "H:i:s", (int) $timestamp );
		else if( $format == "datetime" )
			$human = date( "d.m.Y - H:i:s", (int) $timestamp );
		else if( $format == "duration" ){
			$hours	= str_pad( floor( $timestamp / 3600 ), 2, 0, STR_PAD_LEFT );
			$timestamp -= (int) $hours * 3600;
			$mins	= str_pad( floor( $timestamp / 60 ), 2, 0, STR_PAD_LEFT );
			$timestamp -= (int) $mins * 60;
			$secs	= str_pad( $timestamp, 2, 0, STR_PAD_LEFT );
			$human	= $hours.":".$mins.":".$secs;
		}
		else if( $format )
			$human = date( $format, (int)$timestamp );
		if( $human !== FALSE )
			return $human;
		return NULL;
	}

	/**
	 *	Converts a human time format to Unix Timestamp.
	 *	@access		public
	 *	@static
	 *	@param		string	$string			Human time
	 *	@param		string	$format			Format of human time (date|monthdate|datetime)
	 *	@return		int
	 *	@todo		finish Implementation
	 */
	public static function convertToTimestamp( string $string, string $format )
	{
		$timestamp	= 0;
		if( $string ){
			if( $format == "date" ){
				$parts = explode( ".", $string );
				if( count( $parts ) != 3 )
					throw new InvalidArgumentException( 'Invalid format, must be: [DD.MM.YY]');
				$timestamp = mktime( 0, 0, 0, $parts[1], $parts[0], $parts[2] );
			}
			else if( $format == "monthdate" ){
				if( substr_count( $string, "." ) != 1 )
					throw new InvalidArgumentException( 'Invalid format, must be: [MM.YY]');
				$parts = explode( ".", $string );
				$timestamp = mktime( 0, 0, 0, $parts[0], 1, $parts[1] );
			}
			else if( $format == "time" ){
				if( !substr_count( $string, ":" ) )
					throw new InvalidArgumentException( 'Invalid format, must be: [hh.mm.ss]');
				$parts = explode( ":", $string );
				$timestamp = mktime( $parts[0], $parts[1], $parts[2], 1, 1, 0 );
			}
			else if( $format == "year" ){
				$timestamp = mktime( 0, 0, 0, 1, 1, (int)$string );
			}
			else if( $format == "duration" ){
				if( !substr_count( $string, ":" ) )
					throw new InvalidArgumentException( 'Invalid format, must be: [(hh:)mm:ss]');
				if( substr_count( $string, ":" ) < 2 )
					$string = "0:".$string;
				$parts = explode( ":", $string );
				$timestamp = ( (int) $parts[0] * 3600 ) + ( (int) $parts[1] * 60 ) + (int) $parts[2];
			}
			else if( $format ){
				$pattern1	= "@^([a-z])(.)([a-z])(.)([a-z])(.)?([a-z])?(.)?([a-z])?(.)?([a-z])?$@iu";
				$pattern2	= "@^(\d+)(.)(\d+)(.)(\d+)(.)?(\d+)?(.)?(\d+)?$@";
				$matches1 = [];
				$matches2 = [];
				preg_match_all( $pattern1, $format, $matches1 );
				preg_match_all( $pattern2, $string, $matches2 );
				foreach( $matches1 as $match_key => $match_array )
					if( isset( $match_array[0] ) )
						$matches1[$match_key] = $match_array[0];
				foreach( $matches2 as $match_key => $match_array )
					if( isset( $match_array[0] ) )
						$matches2[$match_key] = $match_array[0];
				$components = [
					"d"	=> "day",
					"j"	=> "day",
					"m"	=> "month",
					"n"	=> "month",
					"Y"	=> "year",
					"y"	=> "year",
					"H"	=> "hour",
					"G"	=> "hour",
					"i"	=> "minute",
					"s"	=> "second"
				];
				$parts	= [];
				foreach( $components as $key => $name ){
					${$name}	= 0;
					if( array_search( $key, $matches1 ) )
						if( isset( $matches2[array_search( $key, $matches1 )] ) )
							$parts[$name] = $matches2[array_search( $key, $matches1 )];
				}

				$timestamp = mktime( $parts['hour'], $parts['minute'], $parts['second'], $parts['month'], $parts['day'], $parts['year'] );
				print_m( get_defined_vars() );
				die;
			}
		}
		return $timestamp;
	}
}

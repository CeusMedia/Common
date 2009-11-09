<?php
/**
 *	Converting Unix Timestamps to Human Time in different formats and backwards.	
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
 *	@version		0.5
 */
/**
 *	Converting Unix Timestamps to Human Time in different formats and backwards.	
 *	@category		cmClasses
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 *	@todo			revise, internationalise
 */
class Alg_Time_Converter
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
	public static function complementMonthDate( $string, $mode = 0 )
	{
		$string	= trim( $string );
		if( preg_match( "@^[0-9]{1,2}\.([0-9]{2}){1,2}$@", $string ) )
		{
			$string	= "01.".$string;
		}
		else if( preg_match( "@^([0-9]{2}){1,2}-[0-9]{1,2}$@", $string ) )
		{
			$string	.= "-01";
		}
		else if( preg_match( "@^[0-9]{1,2}/([0-9]{2}){1,2}$@", $string ) )
		{
			$pos	= strpos( $string, "/" );
			$string	= substr( $string, 0, $pos )."/01".substr( $string, $pos );
		}
		else
			return $string;
		$time	= strtotime( $string );
		if( $time == false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been complemented.' );
		
		$string		= date( "c", $time );
		if( $mode )
		{
			$string		= date( "c", $time + 24 * 60 * 60 -1 );
			$complement	= date( "t", $time );
			$string		= str_replace( "-01T", "-".$complement."T", $string );
		}
		return $string;
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
	public static function convertToTimestamp ($string, $format )
	{
		$timestamp	= 0;
		if( $string )
		{
			if( $format == "date" )
			{
				if( substr_count( $string, "." ) != 2 )
					return false;
				$parts = explode( ".", $string );
				$timestamp = mktime( 0, 0, 0, $parts[1], $parts[0], $parts[2] );
			}
			else if( $format == "monthdate" )
			{
				if( substr_count( $string, "." ) != 1 )
					return false;
				$parts = explode( ".", $string );
				$timestamp = mktime( 0, 0, 0, $parts[0], 1, $parts[1] );
			}
			else if( $format == "time" )
			{
				if( !substr_count( $string, ":" ) )
					return false;
				$parts = explode( ":", $string );
				$timestamp = mktime( $parts[0], $parts[1], $parts[2], 1, 1, 0 );
			}
			else if( $format == "year" )
			{
				$timestamp = mktime( 0, 0, 0, 1, 1, (int)$string );
			}
			else if( $format == "duration" )
			{
				if( !substr_count( $string, ":" ) ) return false;
				if( substr_count( $string, ":" ) < 2 )
					$string = "0:".$string;
				$parts = explode( ":", $string );
				$timestamp = $parts[0]*3600 + $parts[1]*60 + $parts[2];
			}
			else if( $format )
			{
				$pattern1	= "@^([a-z])(.)([a-z])(.)([a-z])(.)?([a-z])?(.)?([a-z])?(.)?([a-z])?$@iu";
				$pattern2	= "@^([0-9]+)(.)([0-9]+)(.)([0-9]+)(.)?([0-9]+)?(.)?([0-9]+)?$@";
				$matches1 = array();
				$matches2 = array();
				preg_match_all( $pattern1, $format, $matches1 );
				preg_match_all( $pattern2, $string, $matches2 );
				foreach( $matches1 as $match_key => $match_array )
					if( isset( $match_array[0] ) )
						$matches1[$match_key] = $match_array[0];
				foreach( $matches2 as $match_key => $match_array )
					if( isset( $match_array[0] ) )
						$matches2[$match_key] = $match_array[0];
				$components = array(
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
					);
				foreach( $components as $key => $name )
				{
					$$name	= 0;
					if( array_search( $key, $matches1 ) )
						if( isset( $matches2[array_search( $key, $matches1 )] ) )
							$$name = $matches2[array_search( $key, $matches1 )];
				}
			
				$timestamp = mktime( $hour, $minute, $second, $month, $day, $year );
				print_m( get_defined_vars() );
				die;
			}
		}
		return $timestamp;
	}

	/**
	 *	Converts Unix Timestamp to a human time format.
	 *	@access		public
	 *	@static
	 *	@param		string	$timestamp		Unix Timestamp	
	 *	@param		string	$format			Format of human time (date|monthdate|datetime|duration|custom format)
	 *	@return		string
	 */
	public static function convertToHuman( $timestamp, $format )
	{
		$human = "";
		if( $format == "date" )
			$human = date( "d.m.Y", (int) $timestamp );
		else if( $format == "monthdate" )
			$human = date( "m.Y", (int) $timestamp );
		else if( $format == "time" )
			$human = date( "H:i:s", (int) $timestamp );
		else if( $format == "datetime" )
			$human = date( "d.m.Y - H:i:s", (int) $timestamp );
		else if( $format == "duration" )
		{
			$hours	= str_pad( floor( $timestamp / 3600 ), 2, 0, STR_PAD_LEFT );
			$timestamp -= $hours * 3600;
			$mins	= str_pad( floor( $timestamp / 60 ), 2, 0, STR_PAD_LEFT );
			$timestamp -= $mins * 60;
			$secs	= str_pad( $timestamp, 2, 0, STR_PAD_LEFT );
			$human	= $hours.":".$mins.":".$secs;
		}
		else if( $format )
			$human = date( $format, (int)$timestamp );
		if( $human )
			return $human;
	}
	
	public static function convertTimeToHuman( $seconds )
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
		
		$string	= $years."a ".$days."d ".$hours."h ".$mins."m ".$seconds."s";
		return $string;
	}
}
?>

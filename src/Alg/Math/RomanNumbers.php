<?php
/**
 *	Convertion between roman and arabic number system.
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
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			22.06.2005
 */
/**
 *	Convertion between roman and arabic number system.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			22.06.2005
 */
class Alg_Math_RomanNumbers
{
	/**	@var	array	$roman		Map of roman numbers and shortcut placeholders*/
	protected static $roman	= array(
		"I"		=> 1,			"A"		=> 4,
		"V"		=> 5,			"B"		=> 9,
		"X"		=> 10,			"E"		=> 40,
		"L"		=> 50,			"F"		=> 90,
		"C"		=> 100,			"G"		=> 400,
		"D"		=> 500,			"H"		=> 900,
		"M"		=> 1000,		"J"		=> 4000,
		"P"		=> 5000,		"K"		=> 9000,
		"Q"		=> 10000,		"N"		=> 40000,
		"R"		=> 50000,		"W"		=> 90000,
		"S"		=> 100000,		"Y"		=> 400000,
		"T"		=> 500000,		"Z"		=> 900000,
		"U"		=> 1000000
	);
	/**	@var	array	$shorts		Map of shortcuts in roman number system */
	protected static $shorts	= array(
		"A"	=> "IV",				"B"	=> "IX",
		"E"	=> "XL",				"F"	=> "XC",
		"G"	=> "CD",				"H"	=> "CM",
		"J"	=> "MP",				"K"	=> "MQ",
		"N"	=> "QR",				"W"	=> "QS",
		"Y"	=> "ST",				"Z"	=> "SU"
	);

	/**
	 *	Converts and returns a roman number as arabian number.
	 *	@access
	 *	@static
	 *	@param		string		$roman		Roman number
	 *	@return		integer
	 */
	public static function convertFromRoman( $roman )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		//  prove roman number by clearing all valid numbers
		$_r = str_replace( array_keys( $this->roman ), "", $roman );
		//  some numbers are invalid
		if( strlen( $_r ) )
			throw new InvalidArgumentException( "Roman '".$roman."' is invalid." );
		//  initiating integer
		$integer = 0;
		$keys	= array_keys( $this->shorts );
		$values	= array_values( $this->shorts );
		//  resolve shortcuts
		$roman = str_replace( $values, $keys, $roman );
		//  all roman number starting with biggest
		foreach( $this->roman as $key => $value )
		{
			//  amount of roman numbers of current value
			$count = substr_count( $roman, $key );
			//  increase integer by amount * current value
			$integer += $count * $value;
			//  remove current roman numbers
			$roman = str_replace( $key, "", $roman );
		}
		return $integer;
	}

	/**
	 *	Converts and returns an arabian number as roman number.
	 *	@access		public
	 *	@static
	 *	@param		int			$integer		Arabian number
	 *	@return		string
	 */
	public static function convertToRoman( $integer )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		arsort( self::$roman );
		//  initiating roman number
		$roman = "";
		//  prove integer by cutting floats
		if( is_numeric( $integer ) && $integer == round( $integer, 0 ) )
		{
			while( $integer > 0 )
			{
				//  all roman number starting with biggest
				foreach( self::$roman as $key => $value )
				{
					//  current roman number is in integer
					if( $integer >= $value )
					{
						//  append roman number
						$roman	.= $key;
						//  decrease integer by current value
						$integer	-= $value;
						break;
					}
				}
			}
			$keys	= array_keys( self::$shorts );
			$values	= array_values( self::$shorts );
			//  realize shortcuts
			$roman	= str_replace( $keys, $values, $roman );
			return $roman;
		}
		else
			throw new InvalidArgumentException( "Integer '".$integer."' is invalid." );
	}
}

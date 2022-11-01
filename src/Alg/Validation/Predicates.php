<?php
/**
 *	Class holding predicates for string validation.
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
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Validation;

use CeusMedia\Common\Alg\Crypt\PasswordStrength;
use CeusMedia\Common\Alg\Time\Converter as TimeConverter;
use Exception;
use InvalidArgumentException;

/**
 *	Class holding predicates for string validation.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Validation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Predicates
{
	/**
	 *	Indicates whether a string is short enough.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		integer		$length		Length to have at most
	 *	@return		boolean
	 */
	public static function hasMaxLength( string $string, int $length ): bool
	{
		return strlen( $string ) <= $length;
	}

	/**
	 *	Indicates whether a string is long enough.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		integer		$length		Length to have at least
	 *	@return		boolean
	 */
	public static function hasMinLength( string $string, int $length ): bool
	{
		return strlen( $string ) >= $length;
	}

	/**
	 *	Indicates whether a password string has a Score.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		integer		$score		Score to a have at least
	 *	@return		boolean
	 */
	public static function hasPasswordScore( string $string, int $score ): bool
	{
		return PasswordStrength::getScore( $string ) >= $score;
	}

	/**
	 *	Indicates whether a password string has a Stength.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		integer		$strength	Strength to a have at least
	 *	@return		boolean
	 */
	public static function hasPasswordStrength( string $string, int $strength ): bool
	{
		return PasswordStrength::getStrength( $string ) >= $strength;
	}

	/**
	 *	Indicates whether a string has a Value.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function hasValue( string $string ): bool
	{
		return trim( $string ) !== '';
	}

	/**
	 *	Indicates whether a string is time formated and is after another point in time.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$point		Point of time to be after
	 *	@return		boolean
	 */
	public static function isAfter( string $string, $point ): bool
	{
		$string	= TimeConverter::complementMonthDate( $string );
		$time	= strtotime( $string );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > $point;
	}

	/**
	 *	Indicates whether a string contains only letters.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isAlpha( string $string ): bool
	{
		return self::isPreg( $string, "/^[a-z0-9]+$/i" );
	}

	/**
	 *	Indicates whether a string contains only letters and digits.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isAlphahyphen( string $string ): bool
	{
		return self::isPreg( $string, "/^[a-z0-9-]+$/i" );
	}

	/**
	 *	Indicates whether a string contains only letters and spaces.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isAlphaspace( string $string ): bool
	{
		return self::isPreg( $string, "/^[a-z0-9 ]+$/i" );
	}

	/**
	 *	Indicates whether a string is time formated and is before another point in time.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$point		Point of time to be before
	 *	@return		boolean
	 */
	public static function isBefore( string $string, $point ): bool
	{
		$string	= TimeConverter::complementMonthDate( $string, 1 );
		$time	= strtotime( $string );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < $point;
	}

	/**
	 *	Indicates whether a string is a valid date.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isDate( string $string ): bool
	{
		try{
			$string	= TimeConverter::complementMonthDate( $string );
			$date	= strtotime( $string );
			return (bool) $date;
		}
		catch( Exception $e ){
			return FALSE;
		}
	}

	/**
	 *	Indicates whether a string contains only numeric characters.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isDigit( string $string ): bool
	{
		return self::isPreg( $string, "/^[0-9]+$/" );
	}

	/**
	 *	Indicates whether a string an valid email address.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isEmail( string $string ): bool
	{
		return self::isPreg( $string, "#^([a-z0-9äöü_.-]+)@([a-z0-9äöü_.-]+)\.([a-z]{2,4})$#i" );
	}

	/**
	 *	Indicates whether a string is a valid file name.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isFilename( string $string ): bool
	{
		return self::isPreg( $string, "'^[a-z0-9!§$%&()=²³{[]}_-;,.+#~@µ`´]+$'i" );
	}

	/**
	 *	Indicates whether a string contains a floating number.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isFloat( string $string ): bool
	{
		return self::isPreg( $string, "/^\d+(\.\d+)?$/" );
	}

	/**
	 *	Indicates whether a string is time formated and is in future.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isFuture( string $string ): bool
	{
		$string	= TimeConverter::complementMonthDate( $string );
		$time	= strtotime( $string );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > time();
	}

	/**
	 *	Indicates whether a string is time formated and is in future, including the actual month
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 *	@todo		test this unit
	 */
	public static function isFutureOrNow( string $string ): bool
	{
		$string	= TimeConverter::complementMonthDate( $string, 1 );
		$time	= strtotime( $string );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > time();
	}

	/**
	 *	Indicates whether a string is larger than a limit.
	 *	Works with float internally.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		boolean
	 */
	public static function isGreater( string $string, $limit ): bool
	{
		return (float) $string > (float) $limit;
	}

	/**
	 *	Indicates whether a string is a valid ID.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isId( string $string ): bool
	{
		return self::isPreg( $string, "'^[a-z][a-z0-9:#/@._-]+$'i" );
	}

	/**
	 *	Indicates whether a string is smaller than a limit.
	 *	Works with float internally.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		boolean
	 */
	public static function isLess( string $string, $limit ): bool
	{
		return (float) $string < (float) $limit;
	}

	/**
	 *	Indicates whether a string contains only letters.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 *	@todo		add Umlauts (äöüßâáàêéèîíìôóòûúù + missing other languages)
	 */
	public static function isLetter( string $string ): bool
	{
		return self::isPreg( $string, "/^[a-z]+$/i" );
	}

	/**
	 *	Indicates whether a string is at most a limit.
	 *	Works with float internally.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		boolean
	 */
	public static function isMaximum( string $string, $limit ): bool
	{
		return (float) $string <= (float) $limit;
	}

	/**
	 *	Indicates whether a string is at least a limit.
	 *	Works with float internally.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		boolean
	 */
	public static function isMinimum( string $string, $limit ): bool
	{
		return (float) $string >= (float) $limit;
	}

	/**
	 *	Indicates whether a string is not 0.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isNotZero( string $string ): bool
	{
		return (string) $string !== '0';
	}

	/**
	 *	Indicates whether a string contains only numeric characters (also ²³).
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isNumeric( string $string ): bool
	{
		return self::isPreg( $string, "/^\d+$/" );
	}

	/**
	 *	Indicates whether a string is time formated and is in past.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isPast( string $string ): bool
	{
		$date	= TimeConverter::complementMonthDate( $string, 1 );
		$time	= strtotime( $date );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < time();
	}

	/**
	 *	Indicates whether a string is time formated and is in past.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 *	@todo		test this unit
	 */
	public static function isPastOrNow( string $string ): bool
	{
		$date	= TimeConverter::complementMonthDate( $string );
		$time	= strtotime( $date );
		if( $time === FALSE )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < time();
	}

	/**
	 *	Indicates whether a string can be matched by a Perl RegEx.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@param		string		$pattern	Perl regular expression
	 *	@return		boolean
	 */
	public static function isPreg( string $string, string $pattern ): bool
	{
		return (bool) preg_match( $pattern, $string );
	}

	/**
	 *	Indicates whether a string an valid URL.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to be checked
	 *	@return		boolean
	 */
	public static function isUrl( string $string ): bool
	{
		$regExp	= "@^([a-z]{3,})://([a-z0-9-_\.]+)/?([\w$-\.+!*'\(\)\@:?#=&/;_]+)$@i";
		return self::isPreg( $string, $regExp );
	}
}

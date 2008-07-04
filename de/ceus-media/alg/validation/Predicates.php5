<?php
import( 'de.ceus-media.alg.TimeConverter' );
/**
 *	Class holding Predicates for String Validation.
 *	@package		alg.validation
 *	@uses			Alg_TimeConverter
 *	@uses			Alg_Crypt_PasswordStrength
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
/**
 *	Class holding Predicates for String Validation.
 *	@package		alg.validation
 *	@uses			Alg_TimeConverter
 *	@uses			Alg_Crypt_PasswordStrength
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
class Alg_Validation_Predicates
{
	/**
	 *	Indicates whether a String is short enough.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function hasMaxLength( $string, $length )
	{
		return strlen( $string ) <= $length;
	}
	
	/**
	 *	Indicates whether a String is long enough.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function hasMinLength( $string, $length )
	{
		return strlen( $string ) >= $length;
	}
	
	/**
	 *	Indicates whether a Password String has a Stength.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		int			$strength	Strength to a have at least
	 *	@return		bool
	 */
	public static function hasPasswordStrength( $string, $strength )
	{
		import( 'de.ceus-media.alg.crypt.PasswordStrength' );
		return Alg_Crypt_PasswordStrength::getStrength( $string ) >= $strength;
	}
	
	/**
	 *	Indicates whether a Password String has a Score.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		int			$score		Score to a have at least
	 *	@return		bool
	 */
	public static function hasPasswordScore( $string, $score )
	{
		return Alg_Crypt_PasswordStrength::getScore( $string ) >= $score;
	}
	
	/**
	 *	Indicates whether a String has a Value.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function hasValue( $string )
	{
		return $string != "";
	}
	
	/**
	 *	Indicates whether a String is time formated and is after another point in time.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$point		Point of Time to be after
	 *	@return		bool
	 */
	public static function isAfter( $string, $point )
	{
		$string	= Alg_TimeConverter::complementMonthDate( $string );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > $point;
	}

	/**
	 *	Indicates whether a String contains only letters.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isAlpha( $string )
	{
		return self::isPreg( $string, "/^[a-z0-9]+$/i" );
	}

	/**
	 *	Indicates whether a String contains only letters and digits.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isAlphahyphen( $string )
	{
		return self::isPreg( $string, "/^[a-z0-9-]+$/i" );
	}

	/**
	 *	Indicates whether a String contains only letters and spaces.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isAlphaspace( $string )
	{
		return self::isPreg( $string, "/^[a-z0-9 ]+$/i" );
	}

	/**
	 *	Indicates whether a String is time formated and is before another point in time.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$point		Point of Time to be before
	 *	@return		bool
	 */
	public static function isBefore( $string, $point )
	{
		$string	= Alg_TimeConverter::complementMonthDate( $string, 1 );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < $point;
	}
	
	/**
	 *	Indicates whether a String is a valid Date.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isDate( $string )
	{
		try
		{
			$string	= Alg_TimeConverter::complementMonthDate( $string );
			$date	= strtotime( $string );
			return (bool) $date;
		}
		catch( Exception $e )
		{
			return false;
		}
	}

	/**
	 *	Indicates whether a String contains only numeric characters.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isDigit( $string )
	{
		return self::isPreg( $string, "/^[0-9]+$/" );
	}

	/**
	 *	Indicates whether a String an valid eMail address.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isEmail( $string )
	{
		return self::isPreg( $string, "#^([a-z0-9äöü_.-]+)@([a-z0-9äöü_.-]+)\.([a-z]{2,4})$#i" );
	}
	
	/**
	 *	Indicates whether a String can be matched by a POSIX RegEx.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$pattern	POSIX regular expression
	 *	@return		bool
	 */
	public static function isEreg( $string, $pattern )
	{
		return ereg( $pattern, $string );
	}
	
	/**
	 *	Indicates whether a String can be matched by a case insensitive POSIX RegEx.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$pattern	POSIX regular expression
	 *	@return		bool
	 */
	public static function isEregi( $string, $pattern )
	{
		return eregi( $pattern, $string );
	}

	/**
	 *	Indicates whether a String contains a floating number.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isFloat( $string )
	{
		return self::isPreg( $string, "/^\d+(\.\d+)?$/" );
	}

	/**
	 *	Indicates whether a String is time formated and is in future.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isFuture( $string )
	{
		$string	= Alg_TimeConverter::complementMonthDate( $string );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > time();
	}

	/**
	 *	Indicates whether a String is larger than a limit.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		bool
	 */
	public static function isGreater( $string, $limit )
	{
		return (bool) $string > (int) $limit;
	}

	/**
	 *	Indicates whether a String is a valid Id.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isId( $string )
	{
		return self::isPreg( $string, "'^[a-z][a-z0-9:#/@._-]+$'i" );
	}

	/**
	 *	Indicates whether a String is a valid File Name.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isFilename( $string )
	{
		return self::isPreg( $string, "'^[a-z0-9!§$%&()=²³{[]}_-;,.+#~@µ`´]+$'i" );
	}

	/**
	 *	Indicates whether a String is smaller than a limit.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		bool
	 */
	public static function isLess( $string, $limit )
	{
		return (bool) $string < (int) $limit;
	}

	/**
	 *	Indicates whether a String contains only letters.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 *	@todo		add Umlauts (äöüßâáàêéèîíìôóòûúù + missing other languages)
	 */
	public static function isLetter( $string )
	{
		return self::isPreg( $string, "/^[a-z]+$/i" );
	}

	/**
	 *	Indicates whether a String is at most a limit.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		bool
	 */
	public static function isMaximum( $string, $limit )
	{
		return (bool) $string <= (int) $limit;
	}

	/**
	 *	Indicates whether a String is at least a limit.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		bool
	 */
	public static function isMinimum( $string, $limit )
	{
		return (bool) $string >= (int) $limit;
	}

	/**
	 *	Indicates whether a String is not "0".
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isNotZero( $string )
	{
		return "0" ==! (string) $string;
	}

	/**
	 *	Indicates whether a String contains only numeric characters (also ²³).
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isNumeric( $string )
	{
		return self::isPreg( $string, "/^\d+$/" );
	}

	/**
	 *	Indicates whether a String is time formated and is in past.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isPast( $string )
	{
		$string	= Alg_TimeConverter::complementMonthDate( $string, 1 );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < time();
	}

	/**
	 *	Indicates whether a String can be matched by a Perl RegEx.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$pattern	Perl regular expression
	 *	@return		bool
	 */
	public static function isPreg( $string, $pattern )
	{
		return (bool) preg_match( $pattern, $string );
	}

	/**
	 *	Indicates whether a String an valid URL.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isUrl( $string )
	{
		return self::isPreg( $string, "@^([a-z]{3,})://([a-z0-9-_\.]+)/?([\w$-\.+!*'\(\)\@:?#=&/;_]+)$@i" );
	}
}
?>
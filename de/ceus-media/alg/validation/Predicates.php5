<?php
/**
 *	Class holding Predicates for String Validation.
 *	@package		alg.validation
 *	@uses			Alg_Crypt_PasswordStrength
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
/**
 *	Class holding Predicates for String Validation.
 *	@package		alg.validation
 *	@uses			Alg_Crypt_PasswordStrength
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2007
 *	@version		0.6
 */
class Alg_Validation_Predicates
{
	/**
	 *	Complements Month Date Format for Time Predicates with Month Start or Month End for Formats.
	 *	Allowed Formats are: m.y, m.Y, m/y, m/Y, y-m, Y-m 
	 *	@access		protected
	 *	@param		string		$string		String to be complemented
	 *	@param		int			$mode		Complement Mode (0:Month Start, 1:Month End)
	 *	@return		
	 */
	protected function complementMonthDate( $string, $mode = 0 )
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
		
		$complement	= $mode ? date( "t", $time ) : "01";
		$string		= date( "c", $time );
		$string		= str_replace( "-01T", "-".$complement."T", $string );
		return $string;
	}

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
		$string	= self::complementMonthDate( $string );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time > $point;
	}

	/**
	 *	Indicates whether a String contains all allowed characters.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 *	@todo		implement pattern
	 */
	public static function isAll( $string )
	{
		return self::isPreg( $string, "/^.*&$/" );
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
	 *	Indicates whether a String contains only letters, digits and some symbols.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 *	@todo		implement pattern
	 */
	public static function isAlphasymbol( $string )
	{
		return self::isPreg( $string, "/^.*$/" );
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
		$string	= self::complementMonthDate( $string, 1 );
		$time	= strtotime( $string );
		if( $time === false )
			throw new InvalidArgumentException( 'Given Date "'.$string.'" could not been parsed.' );
		return $time < $point;
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
	 *	Indicates whether a String contains only numeric characters while dot is possible.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isDotnumeric( $string )
	{
		return self::isPreg( $string, "/^(\d+|\d*\.\d+)$/" );
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
		return self::isPreg( $string, "/^(\d+(\.|,)\d+)$/" );
	}

	/**
	 *	Indicates whether a String is time formated and is in future.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 */
	public static function isFuture( $string )
	{
		$string	= self::complementMonthDate( $string );
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
		return (int) $string > (int) $limit;
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
	 *	Indicates whether a String is smaller than a limit.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@param		string		$limit		Parameter to be measured with
	 *	@return		bool
	 */
	public static function isLess( $string, $limit )
	{
		return (int) $string < (int) $limit;
	}

	/**
	 *	Indicates whether a String contains only letters.
	 *	@access		public
	 *	@param		string		$string		String to be checked
	 *	@return		bool
	 *	@todo		add Umlauts (äöüßâáàêéèîíìôóòûúù + missing other languages(
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
		return (int) $string <= (int) $limit;
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
		return (int) $string >= (int) $limit;
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
		$string	= self::complementMonthDate( $string, 1 );
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
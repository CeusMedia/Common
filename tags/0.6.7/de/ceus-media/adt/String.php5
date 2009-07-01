<?php
/**
 *	String Class wrapping most of the PHP functions in a usable way.
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
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
/**
 *	String Class wrapping most of the PHP functions in a usable way.
 *	@package		adt
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.7
 *	@version		0.1
 */
class ADT_String
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string			Initial string
	 *	@return		void
	 */
	public function __construct( $string = "" )
	{
		$this->string	= $string;
	}
	
	public function __toString()
	{
#		return $this->render();
		return (string) $this->string;
	}

	/**
	 *	Changes first letter of every word to upper case and returns TRUE of there were changes.
	 *	@access		public
	 *	@return		bool		At least 1 character has been changed
	 */
	public function capitalizeWords()
	{
		$oldString		= $this->string;
		$this->string	= ucwords( $this->string );
		return $this->string !== $oldString;
	}

	/**
	 *	Compares this string to another string.
	 *	Returns negative value is this string is less, positive of this string is greater and 0 if both are equal.
	 *	@access		public
	 *	@param		string		$string			String to compare to
	 *	@param		bool		$caseSense		Flag: be case sensitive
	 *	@return		int			Indicator for which string is less, 0 if equal
	 *	@see		http://www.php.net/manual/en/function.strcmp.php
	 *	@see		http://www.php.net/manual/en/function.strcasecmp.php
	 */
	public function compareTo( $string, $caseSense = TRUE )
	{
		$method	= $caseSense ? "strcmp" : "strcasecmp";
		return call_user_func( $method, $this->string, $string );
	}

	/**
	 *	Counts all occurrences of a string within this string, bounded by offset and limit.
	 *	Note: Offset and limit must by less than the lenth of this string.
	 *	@access		public
	 *	@param		string		$string			String to count
	 *	@param		int			$offset			Offset to start at
	 *	@param		int			$limit			Number of characters after offset
	 *	@return		int			Number of occurrences of string with borders
	 */
	public function countSubstring( $string, $offset = 0, $limit = NULL )
	{
		if( !is_string( $string ) && !( $string instanceof ADT_String ) )
			throw new InvalidArgumentException( 'No string given' );
		if( !is_int( $offset ) )
			throw new InvalidArgumentException( 'Offset must be integer' );
		if( abs( $offset ) > $this->getLength() )
			throw new OutOfBoundsException( 'Offset excesses string length' );
		if( !is_null( $limit ) )
		{
			if( !is_int( $limit ) )
				throw new InvalidArgumentException( 'Limit must be integer' );
			if( $limit && $offset + $limit > $this->getLength() )
				throw new OutOfBoundsException( 'Offset and limit excess string length' );
			return substr_count( $this->string, $string, $offset, $limit );
		}
		return substr_count( $this->string, $string, $offset );
	}

	/**
	 *	Escapes this string by adding slashes.
	 *	@access		public
	 *	@return		int			Number of added slashes
	 */
	public function escape()
	{
		$length			= $this->getLength();
		$this->string	= addslashes( $this->string );
		return $this->getLength() - $length;
	}

	/**
	 *	Extends this string with another and returns number of added characters.
	 *	If left and right is set, 
	 *	@access		public
	 *	@param		int			$length			Length of resulting string
	 *	@param		string		$string			String to extend with
	 *	@param		bool		$left			Extend left side
	 *	@param		bool		$right			Extend right side
	 */
	public function extend( $length, $string = " ", $left = FALSE, $right = TRUE )
	{
		if( !is_int( $length ) )
			throw new InvalidArgumentException( 'Length must be integer' );
		if( $length < $this->getLength() )
			throw new InvalidArgumentException( 'Length cannot be lower than string length' );
		if( !is_string( $string ) && !( $string instanceof ADT_String ) )
			throw new InvalidArgumentException( 'Padding string must be of string' );
		if( !$string )
			throw new InvalidArgumentException( 'Padding string cannot be empty' );

		$oldLength	= $this->getLength();
		$mode		= STR_PAD_RIGHT;
		if( $right && $left )
			$mode	= STR_PAD_BOTH;
		else if( $left )
			$mode	= STR_PAD_LEFT;
		else if( !$right )
			throw new InvalidArgumentException( 'No mode given, set left and/or right to TRUE' );
		$this->string	= str_pad( $this->string, $length, (string) $string, $mode );
		return $this->getLength() - $oldLength;
	}	

	/**
	 *	Returns number of characters of this string.
	 *	@access		public
	 *	@return		int			Number of characters
	 */
	public function getLength()
	{
		return strlen( $this->string );
	}

	/**
	 *	Returns a substring of this string.
	 *	Note: Length can be negative 
	 *	@access		public
	 *	@param		int			$start			Number of character to start at
	 *	@param		int			$length			Number of characters from start
	 *	@return		string		Substring
	 *	@see		http://www.php.net/manual/en/function.substr.php
	 */
	public function getSubstring( $start = 0, $length = NULL )
	{
		if( !is_int( $start ) )
			throw new InvalidArgumentException( 'Start must be integer' );
		if( abs( $start ) > $this->getLength() )
			throw new OutOfBoundsException( 'Start excesses string length' );
		if( !is_null( $length ) )																	//  a length is given
		{
			if( !is_int( $length ) )
				throw new InvalidArgumentException( 'Length must be integer' );
			if( $start >= 0 )																		//  start is postive, starting from left
			{
				if( $length >= 0 && $start + $length > $this->getLength() )							//  length from start is to long
					throw new OutOfBoundsException( 'Start and length excess string length from start (from left)' );
				if( $length < 0 && abs( $length ) > $this->getLength() - $start )					//  length from right is to long
					throw new OutOfBoundsException( 'Length (from right) excesses start (form left)' );
			}
			else																					//  start is negative
			{
				if( $length >= 0 && abs( $start ) < $length )										//  length from start is to long
					throw new OutOfBoundsException( 'Length (from start) excesses string length from start (from right)' );
				if( $length < 0 && abs( $start ) < abs( $length ) )									//  length from right is to long
					throw new OutOfBoundsException( 'Length (from right) excesses start (from right)' );
			}
			return new ADT_String( substr( $this->string, $start, $length ) );
		}
		return new ADT_String( substr( $this->string, $start ) );
	}

	/**
	 *	Indicates whether a string is existing in this string within borders of offset and limit.
	 *	@access		public
	 *	@param		string		$string			String to find
	 *	@param		int			$offset			Offset to start at
	 *	@param		int			$limit			Number of characters after offset
	 *	@return		bool		Found or not
	 */
	public function hasSubstring( $string, $offset = 0, $limit = NULL )
	{
		return (bool) $this->countSubstring( $string, $offset, $limit );
	}

	public function render()
	{
		$arguments	= func_get_args();
		return call_user_func( "sprintf", $this->string, $arguments );
	}

	/**
	 *	Repeats this string.
	 *	If the multiplier is 1 the string will be doubled.
	 *	If the multiplier is 0 there will be no effect.
	 *	Negative multipliers are not allowed.
	 *	@access		public
	 *	@param		int			$multiplier		
	 *	@return		int			Number of added characters
	 */
	public function repeat( $multiplier )
	{
		if( !is_int( $multiplier ) )
			throw new InvalidArgumentException( 'Multiplier must be integer' );
		if( $multiplier < 0 )
			throw new InvalidArgumentException( 'Multiplier must be atleast 0' );
		$length			= $this->getLength();
		$this->string	= str_repeat( $this->string, $multiplier + 1 );
		return $this->getLength() - $length;
	}

	/**
	 *	Replaces all occurrences of a search string by a replacement string.
	 *	The number of replaced occurrences is returned.
	 *	Note: This method is not suitable for regular expressions.
	 *	Note: This method is case sensitive by default
	 *	@access		public
	 *	@param		string		$search			String to be replace
	 *	@param		string		$replace		String to be set in
	 *	@param		int			$count			Number of maximum replacements
	 *	@param		bool		$caseSense		Flag: be case sensitive
	 *	@return		int			Number of replaced occurrences
	 */
	public function replace( $search, $replace, $caseSense = TRUE )
	{
		$count			= 0;
		$method			= $caseSense ? "str_replace" : "str_ireplace";
		$this->string	= call_user_func( $method, $search, $replace, $this->string, &$count );
		return $count;
	}

	/**
	 *	Reverses this string.
	 *	@access		public
	 *	@return		bool		At least 1 character has been changed
	 */
	public function reverse()
	{
		$oldString		= $this->string;
		$this->string	= strrev( $this->string );
		return $this->string !== $oldString;
	}

	/**
	 *	Splits this string into an array either by a delimiter string or an number of characters.
	 *	@access		public
	 *	@param		mixed		$delimiter		Delimiter String or number of characters
	 *	@return		ArrayObject
	 *	@see		http://www.php.net/manual/en/function.explode.php
	 *	@see		http://www.php.net/manual/en/function.str-split.php
	 */
	public function split( $delimiter )
	{
		if( is_int( $delimiter ) )
			$list	= str_split( $this->string, $delimiter );
		else if( is_string( $delimiter ) )
			$list	= explode( $delimiter, $this->string );
		return new ArrayObject( $list );
	}

	/**
	 *	Converts string to camel case (removes spaces and capitalizes all words).
	 *	Use the first parameter to get a string beginning with a low letter.
	 *	@param		bool		$startUpperCase		Start with a upper case letter
	 *	@return		bool		At least 1 character has been changed
	 *	@see		http://en.wikipedia.org/wiki/CamelCase
	 */
	public function toCamelCase( $startUpperCase = TRUE )
	{
		$oldString		= $this->string;
		$this->capitalizeWords();
		$this->replace( " ", "" );
		if( !$startUpperCase )
			$this->toLowerCase( TRUE );
		return $this->string !== $oldString;
	}

	/**
	 *	Changes all upper case characters to lower case.
	 *	@param		bool		Only change first letter (=lcfirst)
	 *	@return		bool		At least 1 character has been changed
	 *	@see		http://www.php.net/manual/en/function.strtolower.php
	 *	@see		http://www.php.net/manual/en/function.lcfirst.php
	 */
	public function toLowerCase( $firstOnly = FALSE )
	{
		$oldString		= $this->string;
		if( $firstOnly && !function_exists( 'lcfirst' ) )
		{
			$this->string	= strtolower( substr( $this->string, 0, 1 ) ).substr( $this->string, 1 );
			return $this->string !== $oldString;
		}
		$method			= $firstOnly ? "lcfirst" : "strtolower";
		$this->string	= call_user_func( $method, $this->string );
		$this->string	= strtolower( $this->string );
		return $this->string !== $oldString;
	}

	/**
	 *	Changes all lower case characters to upper case.
	 *	@param		bool		Only change first letter (=ucfirst)
	 *	@return		bool		At least 1 character has been changed
	 *	@see		http://www.php.net/manual/en/function.strtoupper.php
	 *	@see		http://www.php.net/manual/en/function.ucfirst.php
	 */
	public function toUpperCase( $firstOnly = FALSE )
	{
		$oldString		= $this->string;
		$method			= $firstOnly ? "ucfirst" : "strtoupper";
		$this->string	= call_user_func( $method, $this->string );
		return $this->string !== $oldString;
	}

	/**
	 *	Trims this String and returns number of removed characters.
	 *	@access		public
	 *	@param		bool		$left			Remove from left side
	 *	@param		bool		$right			Remove from right side
	 *	@return		int			Number of removed characters
	 */
	public function trim( $left = TRUE, $right = TRUE )
	{
		$length			= $this->getLength();
		if( $left && $right )
			$this->string	= trim( $this->string );
		else if( $left )
			$this->string	= ltrim( $this->string );
		else if( $right )
			$this->string	= rtrim( $this->string );
		return $length - $this->getLength();
	}

 	/**
 	 *	Unescapes this string by removing slashes.
 	 *	@access		public
 	 *	@return		int			Number of removed slashes
 	 */
 	public function unescape()
 	{
		$length			= $this->getLength();
		$this->string	= stripslashes( $this->string );
		return $length - $this->getLength();
 	}

	/**
	 *	Wraps this string into a left and a right string and returns number of added characters.
	 *	@access		public
	 *	@param		string		$left			String to add left
	 *	@param		string		$right			String to add right
	 *	@return		int			Number of added characters
	 */
	public function wrap( $left = NULL, $right = NULL )
	{
		$length			= $this->getLength();
		$this->string	= (string) $left . $this->string . (string) $right;
		return $this->getLength() - $length;
	}
}
?>
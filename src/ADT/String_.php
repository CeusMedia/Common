<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	String Class wrapping most of the PHP functions in a usable way.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT;

use ArrayObject;
use InvalidArgumentException;
use OutOfBoundsException;

/**
 *	String Class wrapping most of the PHP functions in a usable way.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class String_
{
	protected string $string;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$string			Initial string
	 *	@return		void
	 */
	public function __construct( string $string = '' )
	{
		$this->string	= $string;
	}

	public function __toString(): string
	{
#		return $this->render();
		return $this->string;
	}

	/**
	 *	Changes first letter or every delimited word to upper case and returns TRUE of there were changes.
	 *	@access		public
	 *	@param		string|NULL		$delimiter		Capitalize every word separated by delimiter
	 *	@return		bool		At least 1 character has been changed
	 */
	public function capitalize( ?string $delimiter = NULL ): bool
	{
		$oldString		= $this->string;
		if( $delimiter === NULL ){
			$this->string	= ucfirst( $this->string );
			return $this->string !== $oldString;
		}
		else{
			return $this->capitalizeWords( $delimiter );
		}
	}

	/**
	 *	Changes first letter of every word to upper case and returns TRUE of there were changes.
	 *	@access		public
	 *	@param		string|NULL		$delimiter		Capitalize every word separated by delimiter
	 *	@return		bool		At least 1 character has been changed
	 */
	public function capitalizeWords( ?string $delimiter = NULL ): bool
	{
		$oldString		= $this->string;
		if( NULL === $delimiter || preg_match( "/ +/", $delimiter ) ){
			$this->string	= ucwords( $oldString );
		}
		else{
			$token			= md5( (string) microtime( TRUE ) );
			$work			= str_replace( " ", "{".$token."}", $oldString );
			$work			= str_replace( $delimiter, " ", $work );
			$work			= ucwords( $work );
			$work			= str_replace( " ", $delimiter, $work );
			$this->string	= str_replace( "{".$token."}", " ", $work );
		}
		return $this->string !== $oldString;
	}

	/**
	 *	Compares this string to another string.
	 *	Returns negative value is this string is less, positive of this string is greater and 0 if both are equal.
	 *	@access		public
	 *	@param		string		$string			String to compare to
	 *	@param		bool		$caseSense		Flag: be case-sensitive
	 *	@return		int			Indicator for which string is less, 0 if equal
	 *	@see		https://www.php.net/manual/en/function.strcmp.php
	 *	@see		https://www.php.net/manual/en/function.strcasecmp.php
	 */
	public function compareTo( string $string, bool $caseSense = TRUE ): int
	{
		$method	= $caseSense ? "strcmp" : "strcasecmp";
		return call_user_func( $method, $this->string, $string );
	}

	/**
	 *	Counts all occurrences of a string within this string, bounded by offset and limit.
	 *	Note: Offset and limit must be less than the length of this string.
	 *	@access		public
	 *	@param		String_|string	$string			String to count
	 *	@param		int				$offset			Offset to start at
	 *	@param		int				$limit			Number of characters after offset
	 *	@return		int				Number of occurrences of string with borders
	 */
	public function countSubstring( String_|string $string, int $offset = 0, int $limit = 0 ): int
	{
		if( !is_int( $offset ) )
			throw new InvalidArgumentException( 'Offset must be integer' );
		if( abs( $offset ) > $this->getLength() )
			throw new OutOfBoundsException( 'Offset excesses string length' );
		if( abs( $limit ) > 0 ){
			if( $offset + $limit > $this->getLength() )
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
	public function escape(): int
	{
		$length			= $this->getLength();
		$this->string	= addslashes( $this->string );
		return $this->getLength() - $length;
	}

	/**
	 *	Extends this string with another and returns number of added characters.
	 *	If left and right is set,
	 *	@access		public
	 *	@param		int				$length			Length of resulting string
	 *	@param		String_|string	$string			String to extend with
	 *	@param		bool			$left			Extend left side
	 *	@param		bool			$right			Extend right side
	 *	@return		int
	 */
	public function extend( int $length, String_|string $string = " ", bool $left = FALSE, bool $right = TRUE ): int
	{
		if( $length < $this->getLength() )
			throw new InvalidArgumentException( 'Length cannot be lower than string length' );
		if( 0 === strlen( trim( (string) $string ) ) )
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
	public function getLength(): int
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
	 *	@see		https://www.php.net/manual/en/function.substr.php
	 */
	public function getSubstring( int $start = 0, int $length = 0 ): string
	{
		if( abs( $start ) > $this->getLength() )
			throw new OutOfBoundsException( 'Start excesses string length' );
		//  a length is given
		if( $length > 0  ){
			//  start is positive, starting from left
			if( $start >= 0 ){
				//  length from start is too long
				if( $start + $length > $this->getLength() )
					throw new OutOfBoundsException( 'Start and length excess string length from start (from left)' );
				//  length from right is too long
				if( $length > $this->getLength() - $start )
					throw new OutOfBoundsException( 'Length (from right) excesses start (form left)' );
			}
			//  start is negative
			else{
				//  length from start is too long
				if( abs( $start ) < $length )
					throw new OutOfBoundsException( 'Length (from start) excesses string length from start (from right)' );
			}
			return new String_( substr( $this->string, $start, $length ) );
		}
		return new String_( substr( $this->string, $start ) );
	}

	/**
	 *	Indicates whether a string is existing in this string within borders of offset and limit.
	 *	@access		public
	 *	@param		string		$string			String to find
	 *	@param		int			$offset			Offset to start at
	 *	@param		int			$limit			Number of characters after offset
	 *	@return		bool		Found or not
	 */
	public function hasSubstring( string $string, int $offset = 0, int $limit = 0 ): bool
	{
		return $this->countSubstring( $string, $offset, $limit ) !== 0;
	}

	/**
	 *	Replaces whitespace by hyphen.
	 *	@access		public
	 *	@param		array		$characters		List of characters to replace by hyphen
	 *	@param		string		$hyphen			Hyphen character to replace given characters with
	 *	@return		string
	 */
	public function hyphenate( array $characters = [' '], string $hyphen = '-' ): string
	{
		$string	= $this->string;
		foreach( $characters as $character ){
			$pattern	= '/'.preg_quote( $character, '/' ).'+/s';
			$string		= preg_replace( $pattern, $hyphen, $string );
		}
		return $string;
	}

	/**
	 *	Detects whether string is right-to-left or not.
	 *	Needs file './StringRandALCat.txt' to do so.
	 *	@access		public
	 *	@return		bool
	 */
	public function isRTL(): bool
	{
		$RandALCat	= file( __DIR__.'/StringRandALCat.txt', FILE_IGNORE_NEW_LINES );
		$codePoints	= unpack( 'V*', iconv( 'UTF-8', 'UTF-32LE', $this->string ) );
		foreach( $codePoints as $codePoint ){
			$hexCode	= strtoupper( str_pad( dechex( $codePoint ), 6, '0', STR_PAD_LEFT ) );
			if( array_search( $hexCode, $RandALCat, TRUE ) )
				return true;
		}
		return false;
	}

	public function render( array $variables = []): string
	{
		return vsprintf( $this->string, $variables );
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
	public function repeat( int $multiplier ): int
	{
		if( $multiplier < 0 )
			throw new InvalidArgumentException( 'Multiplier must be at least 0' );
		$length			= $this->getLength();
		$this->string	= str_repeat( $this->string, $multiplier + 1 );
		return $this->getLength() - $length;
	}

	/**
	 *	Replaces all occurrences of a search string by a replacement string.
	 *	The number of replaced occurrences is returned.
	 *	Note: This method is not suitable for regular expressions.
	 *	Note: This method is case-sensitive by default
	 *	@access		public
	 *	@param		string		$search			String to be replaced
	 *	@param		string		$replace		String to be set in
	 *	@param		bool		$caseSense		Flag: be case-sensitive
	 *	@return		int			Number of replaced occurrences
	 */
	public function replace( string $search, string $replace, bool $caseSense = TRUE ): int
	{
		$count	= 0;
		if( $caseSense )
			$this->string	= str_replace( $search, $replace, $this->string, $count );
		else
			$this->string	= str_ireplace( $search, $replace, $this->string, $count );
		return $count;
	}

	/**
	 *	Reverses this string.
	 *	@access		public
	 *	@return		bool		At least 1 character has been changed
	 */
	public function reverse(): bool
	{
		$oldString		= $this->string;
		$this->string	= strrev( $this->string );
		return $this->string !== $oldString;
	}

	/**
	 *	Splits this string into an array either by a delimiter string or a number of characters.
	 *	@access		public
	 *	@param		int|string		$delimiter		Delimiter String or number of characters
	 *	@return		ArrayObject
	 *	@see		https://www.php.net/manual/en/function.explode.php
	 *	@see		https://www.php.net/manual/en/function.str-split.php
	 */
	public function split( int|string $delimiter ): ArrayObject
	{
		$list	= [$this->string];
		if( is_int( $delimiter ) )
			$list	= str_split( $this->string, $delimiter );
		else if( is_string( $delimiter ) )
			$list	= explode( $delimiter, $this->string );
		return new ArrayObject( $list );
	}

	/**
	 *	Converts string to camel case (removes spaces and capitalizes all words).
	 *	Use the first parameter to get a string beginning with a low letter.
	 *	@param		bool		$startUpperCase		Start with an upper case letter
	 *	@return		bool		At least 1 character has been changed
	 *	@see		http://en.wikipedia.org/wiki/CamelCase
	 */
	public function toCamelCase( bool $startUpperCase = TRUE ): bool
	{
		$oldString		= $this->string;
		$this->capitalizeWords();
		$this->replace( " ", "" );
		if( !$startUpperCase )
			$this->string[0]	= strtolower( $this->string[0] );
		return $this->string !== $oldString;
	}

	/**
	 *	Changes all upper case characters to lower case.
	 *	@param		bool		$firstOnly		Only change first letter (=lcfirst)
	 *	@return		bool		At least 1 character has been changed
	 *	@see		https://www.php.net/manual/en/function.strtolower.php
	 *	@see		https://www.php.net/manual/en/function.lcfirst.php
	 */
	public function toLowerCase( bool $firstOnly = FALSE ): bool
	{
		$oldString		= $this->string;
		if( $firstOnly && !function_exists( 'lcfirst' ) ){
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
	 *	@param		bool		$firstOnly		Only change first letter (=ucfirst)
	 *	@return		bool		At least 1 character has been changed
	 *	@see		https://www.php.net/manual/en/function.strtoupper.php
	 *	@see		https://www.php.net/manual/en/function.ucfirst.php
	 */
	public function toUpperCase( bool $firstOnly = FALSE ): bool
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
	public function trim( bool $left = TRUE, bool $right = TRUE ): int
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
 	public function unescape(): int
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
	public function wrap( string $left = '', string $right = '' ): int
	{
		$length			= $this->getLength();
		$this->string	= $left . $this->string . $right;
		return $this->getLength() - $length;
	}
}

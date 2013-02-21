<?php
/**
 *	Converter for Strings using different ways of Camel Case.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.6
 *	@version		$Id$
 */
/**
 *	Converter for Strings using different ways of Camel Case.
 *	@category		cmClasses
 *	@package		Alg.Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.6
 *	@version		$Id$
 */
class Alg_Text_CamelCase
{
	protected static $regExp	= '/^(.*)[\-\_ ](.*)$/';
	public static $lowercaseFirst	= NULL;
	public static $lowercaseLetter	= NULL;

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	@access		public
	 *	@static
	 *	@param		string		$string		String to convert
	 *	@param		bool		$startLow	Flag: convert first Word also to uppercase, use static default if NULL
	 *	@return		string
	 */
	static public function convert( $string, $lowercaseFirst = NULL, $lowercaseLetter = NULL )
	{
		$lowercaseFirst		= is_null( $lowercaseFirst ) ? self::$lowercaseFirst : $lowercaseFirst;
		$lowercaseLetter	= is_null( $lowercaseLetter ) ? self::$lowercaseLetter : $lowercaseLetter;

		if( $lowercaseLetter === TRUE )
			$string	= strToLower( $string );

		if( $lowercaseFirst === TRUE )
			$string[0]	= strToLower( $string[0] );
		else if( $lowercaseFirst === FALSE )
			$string	= ucFirst( $string );

		while( preg_match( self::$regExp, $string, $matches ) )
		  $string	= $matches[1].ucfirst( $matches[2] );
		return $string;
	}

	static public function decode( $string )
	{
		if( !function_exists( 'mb_substr' ) )
			throw new RuntimeException( 'PHP module "mb" is not installed but needed' );
		$state  = 0;
		$pos    = 0;
		while( $pos < mb_strlen( $string, "UTF-8" ) )
		{
			$char		= mb_substr( $string, $pos, 1, "UTF-8" );
			$isUpper	= self::isUpperCharacter( $string, $pos );
			switch( $state )
			{
				case 0:
					$state	= $isUpper ? 2 : 1;
					break;
				case 1:
					if( $isUpper )
					{
						$length	= mb_strlen( $string, "UTF-8" );
						$string	= mb_substr( $string, 0, $pos, "UTF-8" ).'-'.mb_substr( $string, $pos, $length, "UTF-8" );
						$state	= 2;
						$pos++;
					}
					break;
				case 2:
					if( !$isUpper )
					{
						$length	= mb_strlen( $string, "UTF-8" );
						$string	= mb_substr( $string, 0, $pos - 1, "UTF-8" ).'-'.mb_substr( $string, $pos - 1, $length, "UTF-8" );
						$state	= 1;
						$pos++;
					}
					break;
			}
			$pos++;
		}
		$string	= preg_replace( "/-+/", "-", $string );
		$parts	= explode( '-', $string );
		foreach( $parts as $nr => $part )
		{
			if( !( strlen( $part ) > 1 && self::isUpperCharacter( $part, 1 ) ) )
				$parts[$nr]	= mb_strtolower( $part, "UTF-8" );
		}
		return join( " ", $parts );
	}

	static protected function isUpperCharacter( $string, $pos )
	{
		$char	= mb_substr( $string, $pos, 1, "UTF-8" );
		return mb_strtolower( $char, "UTF-8") != $char;
	}
}
?>
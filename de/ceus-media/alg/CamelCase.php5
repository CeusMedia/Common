<?php
/**
 *	Converter for Strings using different ways of Camel Case.
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
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
 */
/**
 *	Converter for Strings using different ways of Camel Case.
 *	@package		alg
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.10.2008
 *	@version		0.1
 */
class Alg_CamelCase
{
	protected static $regExp	= '/^(.*)[\-\_ ](.*)$/';
	public static $lowercaseFirst	= NULL;
	public static $lowercaseLetter	= NULL;

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	@access		public
	 *	@param		string		$string		String to convert
	 *	@param		bool		$startLow	Flag: convert first Word also to uppercase, use static default if NULL
	 *	@return		string
	 */
	public static function convert( $string, $lowercaseFirst = NULL, $lowercaseLetter = NULL )
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
}
?>
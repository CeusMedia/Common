<?php
/**
 *	Converter for Strings using Pascal Case.
 *
 *	Copyright (c) 2018-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Text;

use RuntimeException;

/**
 *	Converter for Strings using Pascal Case.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2018-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PascalCase
{
	protected static $regExp	= '/^(.*)[\-\_ ](.*)$/';

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	Alias for encode.
	 *	@access		public
	 *	@static
	 *	@param		string		$string				String to convert
	 *	@param		bool		$lowercaseLetters	Flag: convert all letters to lower case before
	 *	@return		string
	 */
	static public function convert( $string, $lowercaseLetters = TRUE )
	{
		return static::encode( $string, $lowercaseLetters );
	}

	static public function decode( $string, $delimiter = ' ' )
	{
		if( !function_exists( 'mb_substr' ) )
			throw new RuntimeException( 'PHP module "mb" is not installed but needed' );

		$result	= '';
		for( $i=0; $i<strlen( $string ); $i++ ){
			$isUpper	= self::isUpperCharacter( $string, $i );
			$string[$i]	= $isUpper ? mb_strtolower( $string[$i] ) : $string[$i];
			if( strlen( $result ) && $isUpper )
				$result	.= $delimiter;
			$result	.= $string[$i];
		}
		return $result;
	}

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	@access		public
	 *	@static
	 *	@param		string		$string				String to convert
	 *	@param		bool		$lowercaseLetters	Flag: convert all letters to lower case before
	 *	@return		string
	 */
	static public function encode( $string, $lowercaseLetters = TRUE )
	{
		if( $lowercaseLetters === TRUE )
			$string	= mb_strtolower( $string );

		$string	= ucFirst( $string );
		while( preg_match( static::$regExp, $string, $matches ) )
		  $string	= $matches[1].ucfirst( $matches[2] );
		return $string;
	}

	static protected function isUpperCharacter( $string, $pos )
	{
		$char	= mb_substr( $string, $pos, 1, "UTF-8" );
		return mb_strtolower( $char, "UTF-8") != $char;
	}

	static public function toCamelCase( $string )
	{
		return CamelCase::encode( static::decode( $string ) );
	}

	static public function toSnakeCase( $string ){
		return SnakeCase::encode( static::decode( $string ) );
	}

	static public function validate( $string )
	{
		for( $i=0; $i<strlen( $string ); $i++ ){
			$isUpper	= static::isUpperCharacter( $string, $i );
			if( $i == 0 && !$isUpper )
				return FALSE;
			if( $i > 0 && !preg_match( '/[A-Za-z0-9]$/', $string[$i] ) )
				return FALSE;
		}
		return TRUE;
	}
}

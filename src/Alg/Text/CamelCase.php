<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Converter for Strings using different ways of Camel Case.
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
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Text;

use RuntimeException;

/**
 *	Converter for Strings using different ways of Camel Case.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Text
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2008-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class CamelCase
{
	protected static string $regExp	= '/^(.*)[\-\_ ](.*)$/';

	/**
	 *	Convert a String to Camel Case, removing all spaces and underscores and capitalizing all Words.
	 *	Alias for encode.
	 *	@access		public
	 *	@static
	 *	@param		string		$string				String to convert
	 *	@param		bool		$lowercaseLetters	Flag: convert all letters to lower case before
	 *	@return		string
	 */
	public static function convert( string $string, bool $lowercaseLetters = TRUE ): string
	{
		return static::encode( $string, $lowercaseLetters );
	}

	public static function decode( string $string, string $delimiter = ' ' ): string
	{
		if( !function_exists( 'mb_substr' ) )
			throw new RuntimeException( 'PHP module "mb" is not installed but needed' );

		$result	= '';
		for( $i=0; $i<strlen( $string ); $i++ ){
			$isUpper	= static::isUpperCharacter( $string, $i );
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
	public static function encode( string $string, bool $lowercaseLetters = TRUE ): string
	{
		$string[0]	= mb_strtolower( $string[0] );
		if( $lowercaseLetters === TRUE )
			$string	= mb_strtolower( $string );

		while( preg_match( static::$regExp, $string, $matches ) )
		  $string	= $matches[1].ucfirst( $matches[2] );
		return $string;
	}

	public static function toPascalCase( string $string ): string
	{
		return PascalCase::encode( static::decode( $string ) );
	}

	public static function toSnakeCase( string $string ): string
	{
		return SnakeCase::encode( static::decode( $string ) );
	}

	public static function validate( string $string ): bool
	{
		for( $i=0; $i<strlen( $string ); $i++ ){
			$isUpper	= static::isUpperCharacter( $string, $i );
			if( $i == 0 && $isUpper )
				return FALSE;
			if( $i > 0 && !preg_match( '/[A-Za-z\d]$/', $string[$i] ) )
				return FALSE;
		}
		return TRUE;
	}

	protected static function isUpperCharacter( string $string, int $pos ): bool
	{
		$char	= mb_substr( $string, $pos, 1, "UTF-8" );
		return mb_strtolower( $char, "UTF-8") != $char;
	}
}

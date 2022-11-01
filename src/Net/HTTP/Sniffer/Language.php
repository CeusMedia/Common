<?php
/**
 *	Sniffer for Languages accepted by an HTTP Request.
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
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

/**
 *	Sniffer for Languages accepted by an HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Language
{
	/**	@var		string				$pattern	Reg Ex Pattern */
	protected static string $pattern	= '/^([a-z]{1,8}(?:-[a-z]{1,8})*)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns preferred allowed and accepted Language from HTTP_ACCEPT_LANGUAGE.
	 *	@access		public
	 *	@static
	 *	@param		array			$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string|NULL		$default		Default Languages supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public static function getLanguage( array $allowed, ?string $default = NULL ): ?string
	{
		$accept	= getEnv( 'HTTP_ACCEPT_LANGUAGE' );
		return self::getLanguageFromString( $accept, $allowed, $default );
	}

	/**
	 *	Returns preferred allowed and accepted Language from String.
	 *	@access		public
	 *	@static
	 *	@param		string			$string		Array of Languages supported and allowed by the Application
	 *	@param		array			$allowed		Array of Languages supported and allowed by the Application
	 *	@param		string|NULL		$default		Default Languages supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public static function getLanguageFromString( string $string, array $allowed, ?string $default = NULL ): ?string
	{
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string );
		$currentLanguage	= $default;
		$currentQuality		= 0;
		foreach( $accepted as $accept ){
			if( !preg_match( self::$pattern, $accept, $matches ) )
				continue;
			$languageCode = explode ( '-', $matches[1] );
			$languageQuality =  isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			while( count( $languageCode ) ){
				if( in_array( strtolower( join( '-', $languageCode ) ), $allowed ) ){
					if( $languageQuality > $currentQuality ){
						$currentLanguage	= strtolower( join( '-', $languageCode ) );
						$currentQuality		= $languageQuality;
						break;
					}
				}
				array_pop( $languageCode );
			}
		}
		return $currentLanguage;
	}
}

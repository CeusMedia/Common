<?php
/**
 *	Sniffer for Character Sets accepted by an HTTP Request.
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
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

use CeusMedia\Common\Exception\Data\Missing as DataMissingException;

/**
 *	Sniffer for Character Sets accepted by an HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Charset
{
	/**	@var		string		$pattern	Reg Ex Pattern */
	protected static string $pattern	= '/^([0-9a-z-]+)(?:;\s*q=(0(?:\.[0-9]{1,3})?|1(?:\.0{1,3})?))?$/i';

	/**
	 *	Returns preferred allowed and accepted Character Set from HTTP_ACCEPT_CHARSET.
	 *	@access		public
	 *	@static
	 *	@param		array			$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string|NULL		$default		Default Character Sets supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public static function getCharset( array $allowed, ?string $default = NULL ): ?string
	{
		$accepted	= getEnv( 'HTTP_ACCEPT_CHARSET' ) ?: '';
		return self::getCharsetFromString( $accepted, $allowed, $default );
	}

	/**
	 *	Returns preferred allowed and accepted Character Set from String.
	 *	@access		public
	 *	@static
	 *	@param		string			$string			String to process
	 *	@param		array			$allowed		Array of Character Sets supported and allowed by the Application
	 *	@param		string|NULL		$default		Default Character Sets supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public static function getCharsetFromString( string $string, array $allowed, ?string $default = NULL ): ?string
	{
		if( 0 === count( $allowed ) )
			throw new DataMissingException( 'List of allowed character sets cannot be empty' );
		if( !$default)
			$default = $allowed[0];
		if( !$string )
			return $default;
		$accepted	= preg_split( '/,\s*/', $string ) ?: [];
		$currentCharset	= $default;
		$currentQuality	= 0;
		foreach( $accepted as $accept ){
			if( !preg_match ( self::$pattern, $accept, $matches ) )
				continue;
			$charsetQuality	= isset( $matches[2] ) ? (float) $matches[2] : 1.0;
			if( $charsetQuality > $currentQuality ){
				$currentCharset	= strtolower( $matches[1] );
				$currentQuality	= $charsetQuality;
			}
		}
		return $currentCharset;
	}
}

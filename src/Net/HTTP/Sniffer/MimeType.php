<?php
/**
 *	Sniffer for Mime Types accepted by an HTTP Request.
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

/**
 *	Sniffer for Mime Types accepted by an HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MimeType
{
	/**
	 *	Returns preferred allowed and accepted Mime Types.
	 *	@access		public
	 *	@static
	 *	@param		array			$allowed		Array of Mime Types supported and allowed by the Application
	 *	@param		string|NULL		$default		Default Mime Types supported and allowed by the Application
	 *	@return		string|NULL
	 */
	public static function getMimeType( array $allowed, ?string $default = NULL ): ?string
	{
		if( !$default)
			$default = $allowed[0];
		$pattern	= '@^([a-z*+]+(/[a-z*+]+)*)(?:;\s*q=(0(?:\.\d{1,3})?|1(?:\.0{1,3})?))?$@i';
		$accepted	= getEnv( 'HTTP_ACCEPT' );
		if( !$accepted )
			return $default;

		$quality	= 0;
		$mimeType	= $default;
		foreach( preg_split( '/,\s*/', $accepted ) as $accept ){
			if( !preg_match ( $pattern, $accept, $matches ) )
				continue;
			$mimeCode = explode ( '/', $matches[1] );
			$mimeQuality =  isset( $matches[3] ) ? (float) $matches[3] : 1.0;
			while( count( $mimeCode ) ){
				if( in_array( strtolower( join( '/', $mimeCode ) ), $allowed ) ){
					if( $mimeQuality > $quality ){
						$mimeType	= strtolower( join( '/', $mimeCode ) );
						$quality	= $mimeQuality;
						break;
					}
				}
				array_pop( $mimeCode );
			}
		}
		return $mimeType;
	}
}

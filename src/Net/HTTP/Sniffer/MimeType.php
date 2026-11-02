<?php

declare(strict_types=1);

/**
 *	Sniffer for Mime Types accepted by an HTTP Request.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Sniffer;

use CeusMedia\Common\Env;
use CeusMedia\Common\Exception\Data\Missing as DataMissingException;

/**
 *	Sniffer for Mime Types accepted by an HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
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
		if( 0 === count( $allowed ) )
			throw new DataMissingException( 'List of allowed mime types cannot be empty' );
		if( !$default)
			$default = $allowed[0];
		$accepted	= Env::getServerVariable( 'HTTP_ACCEPT' );
		if( !$accepted )
			return $default;
		$quality	= 0.0;
		$mimeType	= $default;
		foreach( explode( ',', $accepted ) as $accept ){
			$parts			= explode( ';', $accept );
			$acceptedType	= strtolower( trim( (string) array_shift( $parts ) ) );
			$acceptedQuality	= 1.0;
			foreach( $parts as $parameter )
				if( 1 === preg_match( '/^\s*q\s*=\s*([0-9.]+)\s*$/i', $parameter, $matches ) )
					$acceptedQuality	= (float) $matches[1];
			if( $acceptedQuality <= $quality )
				continue;
			foreach( $allowed as $allowedType ){
				if( static::matches( $acceptedType, strtolower( $allowedType ) ) ){
					$mimeType	= $allowedType;
					$quality	= $acceptedQuality;
					break;
				}
			}
		}
		return $mimeType;
	}

	/**
	 *	Indicates whether an accepted MIME type (maybe with wildcards like text/* or a single star) matches a MIME type.
	 *	@access		protected
	 *	@static
	 *	@param		string		$accepted		Accepted MIME type, lowercase
	 *	@param		string		$mimeType		MIME type to check, lowercase
	 *	@return		bool
	 */
	protected static function matches( string $accepted, string $mimeType ): bool
	{
		if( $accepted === $mimeType || '*/*' === $accepted || '*' === $accepted )
			return TRUE;
		if( str_ends_with( $accepted, '/*' ) )
			return str_starts_with( $mimeType, substr( $accepted, 0, -1 ) );
		return FALSE;
	}
}

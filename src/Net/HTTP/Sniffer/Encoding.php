<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Sniffer for Encoding Methods accepted by an HTTP Request.
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
use InvalidArgumentException;

/**
 *	Sniffer for Encoding Methods accepted by an HTTP Request.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Sniffer
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Encoding
{
	/**
	 *	Returns preferred allowed and accepted Encoding Method.
	 *	@access		public
	 *	@static
	 *	@param		array		$allowed		Array of Encoding Methods supported and allowed by the Application
	 *	@param		string|NULL	$default		Default Encoding Methods supported and allowed by the Application
	 *	@return		string
	 */
	public static function getEncoding( array $allowed, ?string $default = NULL ): string
	{
		if( 0 === count( $allowed ) )
			throw new DataMissingException( 'List of allowed encodings cannot be empty' );
		if( !$default)
			$default = $allowed[0];
		else if( !in_array( $default, $allowed ) )
			throw new InvalidArgumentException( 'Default Encoding Method must be an allowed Encoding Method.' );

		$accepted	= Env::getServerVariable( 'HTTP_ACCEPT_ENCODING' );
		if( !$accepted )
			return $default;
		$currentCode	= $default;
		$currentQuality	= 0.0;
		foreach( explode( ',', $accepted ) as $accept ){
			$parts		= explode( ';', $accept );
			$code		= strtolower( trim( (string) array_shift( $parts ) ) );
			$quality	= 1.0;
			foreach( $parts as $parameter )
				if( 1 === preg_match( '/^\s*q\s*=\s*([0-9.]+)\s*$/i', $parameter, $matches ) )
					$quality	= (float) $matches[1];
			foreach( $allowed as $allowedCode ){
				if( strtolower( $allowedCode ) !== $code && '*' !== $code )
					continue;
				if( $quality > $currentQuality ){
					$currentCode	= $allowedCode;
					$currentQuality	= $quality;
				}
				break;
			}
		}
		return $currentCode;
	}
}

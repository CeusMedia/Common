<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Compressor for HTTP Request Body Strings.
 *
 *	Copyright (c) 2010-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Response;

use CeusMedia\Common\Exception\NotSupported as NotSupportedException;
use CeusMedia\Common\Net\HTTP\Response as Response;

/**
 *	Compressor for HTTP Request Body Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Compressor
{
	/**
	 *	Applies HTTP Compression to a Response Object.
	 *	@access		public
	 *	@param		Response		$response			Response Object
	 *	@param		string|NULL		$type				Compression type (gzip|deflate)
	 *	@return		Response
	 *	@throws		NotSupportedException				if compression type is not supported
	 */
	public static function compressResponse( Response $response, ?string $type = NULL ): Response
	{
		if( NULL === $type )
			return $response;

		$clone	= clone $response;
		$clone->setBody( static::compressString( $clone->getBody(), $type ) );

		//  send Encoding Headers
		$clone->addHeaderPair( 'Content-Encoding', $type, TRUE );
		$clone->addHeaderPair( 'Vary', "Accept-Encoding", TRUE );
		$clone->setHeader( 'Content-Length', $clone->getBodyLength() );
		return $clone;
	}

	/**
	 *	Applies HTTP Compression to a String.
	 *	@access		public
	 *	@param		string			$content		String to be compressed
	 *	@param		string|NULL		$type			Compression type (gzip|deflate)
	 *	@return		string			Compressed String
	 *	@throws		NotSupportedException			if type is not supported
	 */
	public static function compressString( string $content, ?string $type = NULL ): string
	{
		return match( $type ){
			NULL		=> $content,
			'deflate'	=> gzdeflate( $content ),
			'gzip'		=> gzencode( $content, 9 ),
			default		=> throw NotSupportedException::create()
				->setMessage( 'Compression "' . $type . '" is not supported' )
		};
	}
}

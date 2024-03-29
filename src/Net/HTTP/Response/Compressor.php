<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Compressor for HTTP Request Body Strings.
 *
 *	Copyright (c) 2010-2023 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Response;

use CeusMedia\Common\Net\HTTP\Response as Response;
use InvalidArgumentException;

/**
 *	Compressor for HTTP Request Body Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Compressor
{
	/**
	 *	Applies HTTP Compression to a Response Object.
	 *	@access		public
	 *	@param		Response		$response			Response Object
	 *	@param		string|NULL		$type				Compression type (gzip|deflate)
	 *	@param		boolean			$sendLengthHeader	Flag: add Content-Length Header
	 *	@return		void
	 */
	public static function compressResponse( Response $response, ?string $type = NULL, bool $sendLengthHeader = TRUE )
	{
		if( !$type )
			return;
		$response->setBody( self::compressString( $response->getBody(), $type ) );
		//  send Encoding Header
		$response->addHeaderPair( 'Content-Encoding', $type, TRUE );
		//  send Encoding Header
		$response->addHeaderPair( 'Vary', "Accept-Encoding", TRUE );
		if( $sendLengthHeader )
			//  send Content-Length Header
			$response->addHeaderPair( 'Content-Length', (string) strlen( $response->getBody() ), TRUE );
	}

	/**
	 *	Applied HTTP Compression to a String.
	 *	@access		public
	 *	@param		string			$content		String to be compressed
	 *	@param		string|NULL		$type				Compression type (gzip|deflate)
	 *	@return		string			Compressed String.
	 */
	public static function compressString( string $content, ?string $type = NULL ): string
	{
		switch( $type ){
			case NULL:
				return $content;
			case 'deflate':
				//  compress Content
				return gzdeflate( $content );
			case 'gzip':
				//  compress Content
				return gzencode( $content, 9 );
			//  no valid Compression Method set
			default:
				throw new InvalidArgumentException( 'Compression "'.$type.'" is not supported' );
		}
	}
}

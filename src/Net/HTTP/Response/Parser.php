<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Parser for HTTP Response containing Headers and Body.
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
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP\Response;

use CeusMedia\Common\Net\HTTP\Request as Request;
use CeusMedia\Common\Net\HTTP\Response as Response;
use CeusMedia\Common\Net\HTTP\Response\Decompressor as ResponseDecompressor;
use CeusMedia\Common\Net\HTTP\Header\Field\Parser as HeaderFieldParser;

/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Parser
{
	/**
	 *	Parses Response String and returns resulting Response Object.
	 *	@access		public
	 *	@param		string			$string	Request String
	 *	@return		Response		Response Object
	 */
	public static function fromString( string $string ): Response
	{
#		$string		= trim( $string );
		$parts		= explode( "\r\n\r\n", $string );
		$response	= new Response();
		while( $part = array_shift( $parts ) ){
			$pattern	= '/^([A-Z]+)\/([0-9.]+) ([0-9]{3}) ?(.+)?/';
			if( !preg_match( $pattern, $part ) ){
				array_unshift( $parts, $part );
				break;
			}
			if( 0 === count( $response->headers->getFields() ) )
				$response	= self::parseHeadersFromString( $part );
		}
		$body	= implode( "\r\n\r\n", $parts );

/*		$encodings	= $response->headers->getField( 'content-encoding' );
		while( $encoding = array_pop( $encodings ) ){
			$method	= $encoding->getValue();
			$body	= ResponseDecompressor::decompressString( $body, $method );
		}*/
		$response->setBody( $body );
		return $response;
	}

	public static function parseHeadersFromString( string $string ): Response
	{
		$lines	= explode( "\r\n", $string );
		$firstLine	= array_shift( $lines );
		$pattern	= '/^([A-Z]+)\/([0-9.]+) ([0-9]{3}) ?(.+)?/';
		$matches	= [];
		preg_match_all( $pattern, $firstLine, $matches );
		$response	= new Response( $matches[1][0], $matches[2][0] );
		$response->setStatus( $matches[3][0] );

		foreach( $lines as $line ){
			if( strlen( trim( $line ) ) !== 0 )
				$response->headers->addField( HeaderFieldParser::parse( $line ) );
		}
		return $response;
	}
}

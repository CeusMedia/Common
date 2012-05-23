<?php
/**
 *	Parser for HTTP Response containing Headers and Body.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Net.HTTP.Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
/**
 *	Parser for HTTP Response containing Headers and Body.
 *	@category		cmClasses
 *	@package		Net.HTTP.Response
 *	@uses			Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.0
 *	@version		$Id$
 */
class Net_HTTP_Response_Parser
{
	/**
	 *	Sends given Request and returns resulting Response Object.
	 *	@access		public
	 *	@param		Net_HTTP_Request	$request	Request Object
	 *	@return		Net_HTTP_Response	Response Object
	 */
	public static function fromRequest( Net_HTTP_Request $request )
	{
		$response	= $request->send();
		return self::fromString( $response );
	}

	/**
	 *	Parses Response String and returns resulting Response Object.
	 *	@access		public
	 *	@param		string				$request	Request String
	 *	@return		Net_HTTP_Response	Response Object
	 */
	public static function fromString( $string )
	{
		$string		= trim( $string );
		$parts		= explode( "\r\n\r\n", $string );
		$response	= new Net_HTTP_Response;
		while( $part = array_shift( $parts ) )
		{
			$pattern	= '/^([A-Z]+)\/([0-9.]+) ([0-9]{3}) ?(.+)?/';
			if( !preg_match( $pattern, $part ) )
			{
				array_unshift( $parts, $part );
				break;
			}
			if( !$response->headers->getFields() )
				$response	= self::parseHeadersFromString( $part );
		};
		$body	= implode( "\r\n\r\n", $parts );

/*		$encodings	= $response->headers->getField( 'content-encoding' );
		while( $encoding = array_pop( $encodings ) )
		{
			$method	= $encoding->getValue();
			$body	= Net_HTTP_Response_Decompressor::decompressString( $body, $method );
		}*/
		$response->setBody( $body );
		return $response;
	}

	public static function parseHeadersFromString( $string )
	{
		$lines	= explode( "\r\n", $string );
		$state	= 0;
		foreach( $lines as $line )
		{
			if( !$state )
			{
				$pattern	= '/^([A-Z]+)\/([0-9.]+) ([0-9]{3}) ?(.+)?/';
				$matches	= array();
				preg_match_all( $pattern, $line, $matches );
				$response	= new Net_HTTP_Response( $matches[1][0], $matches[2][0] );
				$response->setStatus( $matches[3][0] );
				$state	= 1;
			}
			else if( $state == 1 )
			{
				if( !trim( $line ) )
					continue;
				$pattern	= '/([a-z-]+):\s(.+)/i';
				if( !preg_match( $pattern, $line ) )
					throw new InvalidArgumentException( 'Invalid header field: '.$line );
				$parts	= explode( ":", $line );
				$key	= array_shift( $parts );
				$value	= trim( implode( ':', $parts ) );
				$response->headers->addField( new Net_HTTP_Header_Field( $key, $value ) );
			}
		}
		return $response;
	}
}
?>
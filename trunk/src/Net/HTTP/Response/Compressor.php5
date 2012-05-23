<?php
/**
 *	Compressor for HTTP Request Body Strings.
 *
 *	Copyright (c) 2010-2012 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Decompressor for HTTP Request Body Strings.
 *	@category		cmClasses
 *	@package		Net.HTTP.Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
class Net_HTTP_Response_Compressor
{
	/**
	 *	Appied HTTP Compression to a Response Object.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response			Response Object
	 *	@param		string				$type				Compression type (gzip|deflate)
	 *	@param		boolean				$sendLengthHeader	Flag: add Content-Length Header
	 *	@return		void
	 */
	public static function compressResponse( Net_HTTP_Response $response, $type = NULL, $sendLengthHeader = TRUE )
	{
		if( !$type )
			return;
		$response->setBody( self::compressString( $response->getBody(), $type ) );
		$response->addHeaderPair( 'Content-Encoding', $type );							//  send Encoding Header
		if( $sendLengthHeader )
		{
			$length		= strlen( $response->getBody() );
			$response->addHeaderPair( 'Content-Length', $length, TRUE );				//  send Content-Length Header
		}
	}

	/**
	 *	Applied HTTP Compression to a String.
	 *	@access		public
	 *	@param		string		$content		String to be compressed
	 *	@return		string		Compressed String.
	 */
	public static function compressString( $content, $type = NULL )
	{
		switch( $type )
		{
			case NULL:
				return $content;
			case 'deflate':
				return gzdeflate( $content );											//  compress Content
			case 'gzip':
				return gzencode( $content );											//  compress Content
			default:																	//  no valid Compression Method set
				throw new InvalidArgumentException( 'Compression "'.$type.'" is not supported' );
		}
	}
}
?>

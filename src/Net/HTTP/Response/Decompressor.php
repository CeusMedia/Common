<?php
/**
 *	Decompressor for HTTP Request Body Strings.
 *
 *	Copyright (c) 2010-2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 */
/**
 *	Decompressor for HTTP Request Body Strings.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP_Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			0.7.1
 */
class Net_HTTP_Response_Decompressor
{
	/**
	 *	Decompresses Content in HTTP Response Object.
	 *	@access		public
	 *	@param		Net_HTTP_Response	$response		HTTP Response Object
	 *	@return		void
	 */
	public static function decompressResponse( Net_HTTP_Response $response )
	{
		$type	= array_pop( $response->getHeader( 'Content-Encoding' ) );
		if( $type )
			$body	= self::decompressString( $response->getBody(), $type );
		$response->setBody( $body );
	}

	/**
	 *	Decompresses compressed Response Content.
	 *	@access		public
	 *	@param		string		$content			Response Content, compressed
	 *	@param		string		$type				Compression Type used (gzip|deflate)
	 *	@return		string
	 */
	public static function decompressString( $content, $type = NULL )
	{
		if( !$type )
			return $content;
		//  open a output buffer
		ob_start();
		switch( strtolower( $type ) )
		{
			case 'deflate':
				$content	= self::inflate( $content );
				break;
			case 'gzip':
				xmp( $content );
				$content	= self::ungzip( $content );
				xmp( $content );
				break;
			default:
				ob_end_clean();
				throw new InvalidArgumentException( 'Decompression method "'.$type.'" is not supported' );
		}
		//  close buffer for PHP error messages
		$output		= ob_get_clean();
		//  could not decompress
		if( $content === FALSE && $output )
			//  throw exception and carry error message
			throw new RuntimeException( $output );
		//  return decompressed response Content
		return $content;
	}

	/**
	 *	Decompresses gzipped String. Function is missing in some PHP Win Builds.
	 *	@access		public
	 *	@param		string		$content		Data String to be decompressed
	 *	@return		string
	 */
	public static function ungzip( $content )
	{
		//  if PHP method has been released
		if( function_exists( 'gzdecode' ) )
			//  use it to decompress the data
			$content	= @gzdecode( $content );
		//  otherwise: own implementation
		else
		{
			//  create temporary file
			$tmp	= tempnam( '/tmp', 'CMC' );
			//  store gzipped data
			@file_put_contents( $tmp, $content );
			//  open output buffer
			ob_start();
			//  read the gzip file to std output
			readgzfile( $tmp );
			@unlink( $tmp );
			//  get decompressed data from output buffer
			$content	= ob_get_clean();
		}
		//  gzencode could decompress
		if( FALSE !== $content )
			//  return decompressed data
			return $content;
		//  throw exception
		throw new RuntimeException( 'Data not decompressable with gzdecode' );
	}

	/**
	 *	Inflates a deflated String.
	 *	@access		public
	 *	@param		string		$content		Data String to be inflated
	 *	@return		string
	 */
	public static function inflate( $content )
	{
		$content	= @gzuncompress( $content );
		if( FALSE !== $content )
			return $content;
		throw new RuntimeException( "Data not decompressable with gzuncompress." );
	}
}

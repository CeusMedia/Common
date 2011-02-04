<?php
/**
 *	Decompressor for HTTP Request Body Strings.
 *
 *	Copyright (c) 2010 Christian Würker (ceusmedia.de)
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
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Decompressor for HTTP Request Body Strings.
 *	@category		cmClasses
 *	@package		Net.HTTP.Response
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
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
		ob_start();																					//  open a output buffer
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
		$output		= ob_get_clean();																//  close buffer for PHP error messages
		if( $content === FALSE && $output )															//  could not decompress
			throw new RuntimeException( $output );												//  throw exception and carry error message
		return $content;																				//  return decompressed response Content
	}

	/**
	 *	Decompresses gzipped String. Function is missing in some PHP Win Builds.
	 *	@access		public
	 *	@param		string		$content		Data String to be decompressed
	 *	@return		string
	 */
	public static function ungzip( $content )
	{
		if( function_exists( 'gzdecode' ) )															//  if PHP method has been released
			$content	= @gzdecode( $content );													//  use it to decompress the data
		else																						//  otherwise: own implementation
		{
			$tmp	= tempnam( '/tmp', 'CMC' );														//  create temporary file
			@file_put_contents( $tmp, $content );													//  store gzipped data
			ob_start();																				//  open output buffer
			readgzfile( $tmp );																		//  read the gzip file to std output
			@unlink( $tmp );
			$content	= ob_get_clean();															//  get decompressed data from output buffer
		}
		if( FALSE !== $content )																	//  gzencode could decompress
			return $content;																			//  return decompressed data
		throw new RuntimeException( 'Data not decompressable with gzdecode' );						//  throw exception
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
?>
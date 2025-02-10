<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Download Provider for Files and Strings.
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
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use RuntimeException;

/**
 *	Download Provider for Files and Strings.
 *	Improved by hints on http://www.media-division.com/the-right-way-to-handle-file-downloads-in-php/
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.media-division.com/the-right-way-to-handle-file-downloads-in-php/
 *	@todo			integrate MIME type detection
 *	@todo  			support download range
 *	@todo  			support x-sendfile, @see https://tn123.org/mod_xsendfile/
 */
class Download
{
	/**
	 *	Applies default HTTP headers of download.
	 *	Also applies content length and last modification date if parameters are set.
	 *	@static
	 *	@access		protected
	 *	@param		int|NULL		$size			File size to send
	 *	@param		int|NULL		$timestamp		File date (as UNIX timestamp) to send
	 *	@return		void
	 */
	protected static function applyDefaultHeaders( ?int $size = NULL, ?int $timestamp = NULL ): void
	{
		header( "Pragma: public" );
		header( "Expires: -1" );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		if( (int) $size > 0 )
			header( "Content-Length: ".( (int) $size ) );
		$timestamp	= ( (float) $timestamp ) > 1 ? $timestamp : time();
		header( "Last-Modified: ".date( 'r', $timestamp ) );

	}

	/**
	 *	Turn off compression on the server.
	 *	@static
	 *	@access		protected
	 *	@return		void
	 */
	protected static function disableCompression(): void
	{
		if( function_exists( 'apache_setenv' ) )
			@apache_setenv( 'no-gzip', '1' );
		@ini_set( 'zlib.output_compression', 'Off' );
	}

	/**
	 *	Sends String for Download.
	 *	@static
	 *	@access		public
	 *	@param		string			$url			File to send
	 *	@param		string|NULL		$filename		Filename of Download
	 *	@param		boolean			$andExit		Flag: quit execution afterwards, default: yes
	 *	@return		void
	 */
	public static function sendFile( string $url, ?string $filename = NULL, bool $andExit = TRUE ): void
	{
		$filename	= strlen( $filename ) ? $filename : basename( $url );
		//  avoid messing with path
		$url		= str_replace( '../', '', $url );
		if( !file_exists( $url ) )
			throw new RuntimeException( 'File "'.$url.'" is not existing' );
		static::clearOutputBuffers();
		static::setMimeType();
		static::disableCompression();
		static::applyDefaultHeaders( filesize( $url ) ?: NULL, filemtime( $url ) ?: NULL );
		header( "Content-Disposition: attachment; filename=\"".$filename."\"" );
		$fp = @fopen( $url, "rb" );
		if( FALSE === $fp )
			header("HTTP/1.0 500 Internal Server Error");
		else
			fpassthru( $fp );
		if( $andExit )
			exit;
	}

	/**
	 *	Sends String for Download.
	 *	@static
	 *	@access		public
	 *	@param		string			$string			String to send
	 *	@param		string  		$filename		Filename of Download
	 *	@param		boolean			$andExit		Flag: quit execution afterwards, default: yes
	 *	@return		void
	 */
	public static function sendString( string $string, string $filename, bool $andExit = TRUE ): void
	{
		static::clearOutputBuffers();
		static::setMimeType();
		static::disableCompression();
		static::applyDefaultHeaders( strlen( $string ) );
		header( "Content-Disposition: attachment; filename=\"".$filename."\"" );
		print( $string );
		if( $andExit )
			exit;
	}

	/**
	 *	Sends Mime Type Header.
	 *	@static
	 *	@access		protected
	 *	@return		void
	 */
	protected static function setMimeType(): void
	{
		$UserBrowser = '';
		if( preg_match( '@Opera(/| )([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'] ) )
			$UserBrowser = "Opera";
		elseif( preg_match( '@MSIE ([0-9].[0-9]{1,2})@', $_SERVER['HTTP_USER_AGENT'] ) )
			$UserBrowser = "IE";
		$mime_type = ( $UserBrowser == 'IE' || $UserBrowser == 'Opera' ) ? 'application/octetstream' : 'application/octet-stream';
		header( "Content-Type: ". $mime_type);
	}

	/**
	 *	Closes active Output Buffers.
	 *	@static
	 *	@access		private
	 *	@return		void
	 */
	protected static function clearOutputBuffers(): void
	{
		while( ob_get_level() )
			ob_end_clean();
	}
}

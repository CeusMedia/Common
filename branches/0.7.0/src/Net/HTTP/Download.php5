<?php
/**
 *	Download Provider for Files and Strings.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
/**
 *	Download Provider for Files and Strings.
 *	@category		cmClasses
 *	@package		net.http
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
class Net_HTTP_Download
{
	/**
	 *	Sends String for Download.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			File to send
	 *	@return		void
	 */
	public static function sendFile( $url )
	{
		self::clearOutputBuffers();
		self::setMimeType();	
		header( "Last-Modified: ".date( 'r',filemtime( $url ) ) );
		header( "Content-Length: ".filesize( $url ) );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Disposition: attachment; filename=".basename( $url ) );
		$fp = @fopen( $url, "rb" );
		if( $fp )
			fpassthru( $fp );
		else
			header("HTTP/1.0 500 Internal Server Error");
		die;
	}

	/**
	 *	Sends String for Download.
	 *	@access		public
	 *	@param		string		$string			String to send
	 *	@param		string		$filename		Filename of Download
	 *	@return		void
	 */
	public function sendString( $string, $filename )
	{
		self::clearOutputBuffers();
		self::setMimeType();	
		header( "Last-Modified: ".date( 'r',time() ) );
		header( "Content-Length: ".strlen( $string ) );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Disposition: attachment; filename=".$filename );
		die( $string );
	}
	
	/**
	 *	Sends Mime Type Header.
	 *	@access		private
	 *	@static
	 *	@return		void
	 */
	private static function setMimeType()
	{
		$UserBrowser = '';
		if( ereg( 'Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'] ) )
			$UserBrowser = "Opera";
		elseif( ereg( 'MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT'] ) )
			$UserBrowser = "IE";
		$mime_type = ( $UserBrowser == 'IE' || $UserBrowser == 'Opera' ) ? 'application/octetstream' : 'application/octet-stream';
		header( "Content-Type: ". $mime_type);
	}
	
	/**
	 *	Closes active Output Buffers.
	 *	@access		private
	 *	@static
	 *	@return		void
	 */
	private static function clearOutputBuffers()
	{
		while( ob_get_level() )
			ob_end_clean();
	}
}
?>
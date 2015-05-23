<?php
/**
 *	Download Provider for Files and Strings.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
/**
 *	Download Provider for Files and Strings.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
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
	 *	@param		string		$filename       Filename of Download
	 *	@param		boolean		$andExit		Flag: quit execution afterwards, default: yes
	 *	@return		void
	 */
	static public function sendFile( $url, $filename = NULL, $andExit = TRUE )
	{
		self::clearOutputBuffers();
		self::setMimeType();
		$filename	= strlen( $filename ) ? $filename : basename( $url );
		if( !file_exists( $url ) )
			throw new RuntimeException( 'File "'.$url.'" is not existing' );
		header( "Last-Modified: ".date( 'r',filemtime( $url ) ) );
		header( "Content-Length: ".filesize( $url ) );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Disposition: attachment; filename=".$filename );
		$fp = @fopen( $url, "rb" );
		if( !$fp )
			header("HTTP/1.0 500 Internal Server Error");
		fpassthru( $fp );
		if( $andExit )
			exit;
	}

	/**
	 *	Sends String for Download.
	 *	@access		public
	 *	@static
	 *	@param		string		$string			String to send
	 *	@param		string		$filename		Filename of Download
	 *	@param		boolean		$andExit		Flag: quit execution afterwards, default: yes
	 *	@return		void
	 */
	static public function sendString( $string, $filename, $andExit = TRUE )
	{
		self::clearOutputBuffers();
		self::setMimeType();
		header( "Last-Modified: ".date( 'r',time() ) );
		header( "Content-Length: ".strlen( $string ) );
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" );
		header( "Content-Disposition: attachment; filename=".$filename );
		print( $string );
		exit;
	}

	/**
	 *	Sends Mime Type Header.
	 *	@access		private
	 *	@static
	 *	@return		void
	 */
	static private function setMimeType()
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
	 *	@access		private
	 *	@static
	 *	@return		void
	 */
	static private function clearOutputBuffers()
	{
		while( ob_get_level() )
			ob_end_clean();
	}
}
?>

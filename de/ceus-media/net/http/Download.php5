<?php
/**
 *	Download Provider for Files and Strings.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
/**
 *	Download Provider for Files and Strings.
 *	@package		net.http
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
class Net_HTTP_Download
{
	/**
	 *	Sends String for Download.
	 *	@access		public
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
	 *	@return		void
	 */
	private static function clearOutputBuffers()
	{
		while( ob_get_level() )
			ob_end_clean();
	}
}
?>
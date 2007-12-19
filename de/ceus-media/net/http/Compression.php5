<?php
import( 'de.ceus-media.file.log.Writer' );
/**
 *	Compresses and sends HTTP Output.
 *	@package		protocol.http
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Compresses and sends HTTP Output.
 *	@package		net.http
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_HTTP_Compression
{
	/**	@var	string		$name			Connection Name for Log File */
	protected $name	= "";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$name			Connection Name for Log File for Compression Statistics
	 *	@return		void
	 */
	public function __construct( $name = "" )
	{
		$this->name = $name;
		ob_start();
		ob_implicit_flush( 0 );
	}

	/**
	 *	Sends checksum and size as 4 characters.
	 *	@access		protected
	 *	@param		int		$value			Checksum or size
	 *	@return		void
	 */
	protected function gzipPrintFourChars( $value )
	{
		$code = "";
		for( $i = 0; $i < 4; $i ++ )
		{
			$code .= chr( $value % 256 );
			$value	= floor( $value / 256 );
		}
		return $code;
	}

	/**
	 *	Sends compressed Content Data.
	 *	@access		public
	 *	@param		string	$logFile		File Name of Log File for Compression Statistics
	 *	@return		void
	 */
	public function putOut( $logFile = false )
	{
		$contents	= ob_get_contents();
		for( $i=0; $i<=ob_get_level(); $i++ )
			ob_end_clean();

		$size		= strlen( $contents );
		$crc		= crc32( $contents );
		$contents	= gzcompress( $contents, 9 );
		$contents	= substr( $contents, 0, strlen( $contents ) - 4 );

		if( $logFile )
			$this->logRatio( $logFile, $size, strlen($contents) );

		header( "Content-Encoding: gzip" );
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $contents;
		echo $this->gzipPrintFourChars( $crc );
		echo $this->gzipPrintFourChars( $size );
		die;
	}
	
	/**
	 *	Writes Compression Statistics to LogFile.
	 *	@access		private
	 *	@param		string	$logfile		Name of LogFile for Compression Statistics
	 *	@param		int		$before		Content Size before Compression
	 *	@param		int		$after		Content Size after Compression
	 *	@return		void
	 */
	protected function logRatio( $logFile, $before, $after )
	{
		$log		= new File_Log_Writer( $logFile );
		$ratio	= round( ( $after / $before * 100 ), 2 );
		$before	= round( $before/1024, 3 )." kB";
		$after	= round( $after/1024, 3 )." kB";
		$log->note( $this->name." (".$before.")->[".$after."] = ".$ratio." %" );
	}
}
?>
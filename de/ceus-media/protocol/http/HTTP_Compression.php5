<?php
import( 'de.ceus-media.file.log.LogFile' );
/**
 *	Compresses and sends HTTP Output.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Object
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Compresses and sends HTTP Output.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Object
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class HTTP_Compression
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$name			Connection name for LogFile for Compression Statistics
	 *	@return		void
	 */
	public function __construct( $name = "" )
	{
		$this->_name = $name;
		ob_start();
		ob_implicit_flush( 0 );
	}

	/**
	 *	Sends checksum and size as 4 characters.
	 *	@access		private
	 *	@param		int		$value			Checksum or size
	 *	@return		void
	 */
	function _gzip_PrintFourChars( $value )
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
	 *	@param		string	$ratio_log		Name of LogFile for Compression Statistics
	 *	@return		void
	 */
	function putOut( $ratio_log = false )
	{
		$contents	= ob_get_contents();
		for( $i=0; $i<=ob_get_level(); $i++ )
			ob_end_clean();

		$size		= strlen( $contents );
		$crc			= crc32( $contents );
		$contents	= gzcompress( $contents, 9 );
		$contents	= substr( $contents, 0, strlen( $contents ) - 4 );

		if( $ratio_log )
			$this->_logRatio( $ratio_log, $size, strlen($contents) );

		header( "Content-Encoding: gzip" );
		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $contents;
		echo $this->_gzip_PrintFourChars( $crc );
		echo $this->_gzip_PrintFourChars( $size );
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
	function _logRatio( $logfile, $before, $after )
	{
		$log		= new LogFile( $logfile );
		$ratio	= round( ( $after / $before * 100 ), 2 );
		$before	= round( $before/1024, 3 )." kB";
		$after	= round( $after/1024, 3 )." kB";
		$log->addEntry( $this->_name." (".$before.")->[".$after."] = ".$ratio." %" );
	}
}
?>
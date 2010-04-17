<?php
import( 'de.ceus-media.file.log.Writer' );
import( 'de.ceus-media.StopWatch' );
/**
 *	Compresses and sends HTTP Output.
 *	@package		protocol.http
 *	@uses			File_Log_Writer
 *	@uses			StopWatch
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Compresses and sends HTTP Output.
 *	@package		net.http
 *	@uses			File_Log_Writer
 *	@uses			StopWatch
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_HTTP_Compression
{
	/**	@var		string		$method		Compression Method to use */	
	protected static $method	= "deflate";
	/**	@var		array		$methods	List of supported Compression Methods (deflate|gzip) */	
	protected static $methods	= array( 'deflate', 'gzip' );
	/**	@var		string		$logFile	File Name of Log File */	
	protected $logFile;
	/**	@var		int			$precision	Precision of mathematical Operations */	
	protected $precision;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $logFile = NULL, $precision = 1 )
	{
		$this->logFile		= $logFile;
		$this->precision	= $precision;
		ob_start();
		ob_implicit_flush( 0 );
	}
	
	/**
	 *	Returns currently set Compression Method.
	 *	@access		public
	 *	@return		string
	 */
	public static function getMethod()
	{
		return self::$method;
	}
	
	/**
	 *	Returns List of supported Compression Methods.
	 *	@access		public
	 *	@return		array
	 */
	public static function getMethods()
	{
		return self::$methods;
	}
	
	/**
	 *	Appeds statistical Data to Log File.
	 *	@access		private
	 *	@param		string	$logfile		Name of LogFile for Compression Statistics
	 *	@param		int		$before		Content Size before Compression
	 *	@param		int		$after		Content Size after Compression
	 *	@return		bool
	 */
	protected static function log( $logFile, $before, $after, $time, $precision = 1 )
	{
		$ratio	= $after / $before * 100;
		$ratio	= round( $ratio, $precision );
		$entry	= time()." ".self::$method." ".$before." ".$after." ".$ratio." ".$time."\n";
		return error_log( $entry, 3, $logFile );
	}

	/**
	 *	Sends compressed Output Buffer Content and returns Length of sent compressed Content.
	 *	@access		public
	 *	@return		int
	 */
	public function send()
	{
		$content	= ob_get_clean();
		return self::sendContent( $content, $this->logFile, $this->precision );
	}

	/**
	 *	Sends compressed Content and returns Length of sent compressed Content statically.
	 *	@access		public
	 *	@param		string	$logFile		File Name of Log File for Compression Statistics
	 *	@return		int
	 */
	public static function sendContent( $content, $logFile = NULL, $precision = 1 )
	{
		$sizeBefore	= strlen( $content );												//  get Length of Content before Compression
		if( headers_sent() )															//  Header are already sent, no Compression possible
		{
			print( $content );															//  send uncompressed Content
			return $sizeBefore;															//  return Length of uncompressed Content
		}
		$watch	= new Stopwatch;														//  start Stopwatch
		switch( self::$method )															//  switch for Compression Method
		{
			case 'deflate':																//  DEFLATE
				$content	= gzcompress( $content );									//  compress Content
				header( "Content-Encoding: deflate" );									//  send Encoding Header
				break;
			case 'gzip':
				$content	= gzencode( $content );										//  compress Content
				header( "Content-Encoding: gzip" );										//  send Encoding Header
				break;
			default:																	//  no valid Compression Method set
				print( $content );														//  send uncompressed Content
				return $sizeBefore;														//  return Length of uncompressed Content
		}
		$sizeAfter	= strlen( $content );												//  get Length of Content after Compression
		ob_start();																		//  open Output Buffer to avoid Problems
		if( $logFile )																	//  Logging is enabled
		{
			$time	= $watch->stop( 6, 0 );												//  get Compression Time
			@self::log( $logFile, $sizeBefore, $sizeAfter, $time, $precision );			//  log statistical Data
		}
		while( ob_get_level() )															//  all open Output Buffers
			ob_end_clean();																//  will be closed
		print( $content );																//  send compressed Content
		flush();
		return $sizeAfter;																//  return Length of compressed Content
	}
	
	/**
	 *	Sets Compression Method statically.
	 *	@access		public
	 *	@param		string		$method		Compression Method to use
	 *	@return		void
	 */
	public static function setMethod( $method )
	{
		if( !( empty( $method ) || in_array( strtolower( $method ), self::$methods ) ) )
			throw new InvalidArgumentException( 'Method "'.$method.'" is not supported ('.implode( ",", self::$methods ).').' );
		self::$method	= strtolower( $method );
	}
}
?>
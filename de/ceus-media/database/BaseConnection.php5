<?php
import( "de.ceus-media.file.log.LogFile" );
/**
 *	Generic Connection.
 *	@package		database
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 */
/**
 *	Generic Connection.
 *	@package		database
 *	@uses			LogFile
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 */
class BaseConnection
{
	var $_connected	= false;
	var $_state		= 0;
	var $_logfile		= "db_error.log";
	var $_log;
	var $_error_report	= 15;

	public function __construct( $logfile = false )
	{
		if( $logfile )
			$this->_logfile	= $logfile;
		$this->_connected = false;
	}

	function Connect( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "connect", $host, $user, $pass, $database );
	}

	function getMicroTime( $end = false )
	{
		$arrTime = explode( " ", microtime() );
		$time = ( doubleval( $arrTime[0] ) + $arrTime[1] ) * 1000;
		return $time;
	}

	function getTimeDifference( $start )
	{
		return sprintf( "%1.4f", $this->getMicroTime( true ) - $start );
	}
	
	function isConnected()
	{
		return (bool) $this->_connected;
	}

	function setLogFile( $filename )
	{
		$this->_logfile = $filename;
	}
	
	
	/**
	 *	Sets level or error reporting ( 0 - none, 1 - log only, 2 - log & warning, 3 - log & error )
	 */
	function setErrorReporting( $value )
	{
		$this->_error_report = $value;
	}
	
	function handleError( $error_code, $error_msg, $query )
	{
		if( $this->_error_report )
		{
			$log = new LogFile( $this->_logfile );
			$log->addEntry( "[".$error_code.": ".$error_msg." in EXECUTE (\"".$query."\")]" );
			if( $this->_error_report == 2 )
				trigger_error( $error_code.": ".$error_msg." in EXECUTE (\"".$query."\")", E_USER_WARNING );
			else if( $this->_error_report == 3 )
				trigger_error( $error_code.": ".$error_msg." in EXECUTE (\"".$query."\")", E_USER_ERROR );
		}
	}
}
?>
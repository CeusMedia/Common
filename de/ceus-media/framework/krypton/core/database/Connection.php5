<?php
import( "de.ceus-media.file.log.Writer" );
/**
 *	Generic Connection.
 *	@package		framework.krypton.core.database
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	Generic Connection.
 *	@package		framework.krypton.core.database
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
class Framework_Krypton_Core_Database_Connection
{
	private $connected	= false;
	private $logfile		= "db_error.log";
	private $error_report	= 15;

	public function __construct( $logfile = false )
	{
		if( $logfile )
			$this->logfile	= $logfile;
		$this->connected = false;
	}

	public function connect( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "connect", $host, $user, $pass, $database );
	}

	protected function getMicroTime( $end = false )
	{
		$arrTime = explode( " ", microtime() );
		$time = ( doubleval( $arrTime[0] ) + $arrTime[1] ) * 1000;
		return $time;
	}

	protected function getTimeDifference( $start )
	{
		return sprintf( "%1.4f", $this->getMicroTime( true ) - $start );
	}

	
	protected function handleError( $error_code, $error_msg, $query )
	{
		if( $this->error_report )
		{
			$log = new File_Log_Writer( $this->logfile );
			$log->note( "[".$error_code.": ".$error_msg." in EXECUTE (\"".$query."\")]" );
			if( $this->error_report == 2 )
				trigger_error( $error_code.": ".$error_msg." in EXECUTE (\"".$query."\")", E_USER_WARNING );
			else if( $this->error_report == 3 )
				trigger_error( $error_code.": ".$error_msg." in EXECUTE (\"".$query."\")", E_USER_ERROR );
		}
	}
	
	/**
	 *	Sets level or error reporting ( 0 - none, 1 - log only, 2 - log & warning, 3 - log & error )
	 */
	public function setErrorReporting( $value )
	{
		$this->error_report = $value;
	}

	function setLogFile( $filename )
	{
		$this->logfile = $filename;
	}
}
?>

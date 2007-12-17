<?php
import( "de.ceus-media.file.log.Writer" );
/**
 *	Abstract Database Connection.
 *	@package		database
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.5
 */
/**
 *	Abstract Database Connection.
 *	@package		database
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.5
 */
abstract class Database_BaseConnection
{
	/**	@var	bool		$connected			State of Connection */
	protected $connected	= false;
	/**	@var	string		$logFile			File Name of Log File */
	protected $logFile		= "db_error.log";
	/**	@var	int			$errorLevel			Level of Error Reporting */
	protected $errorLevel	= 4;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$logFile		FileName of Log File
	 *	@return		void
	 */
	public function __construct( $logFile = false )
	{
		if( $logFile )
			$this->logFile	= $logFile;
		$this->connected = false;
	}

	/**
	 *	Establishs Database Connection.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		string		$user			User Name
	 *	@param		string		$pass			Password
	 *	@param		string		$database		Database Name
	 *	@return		bool
	 */
	public function connect( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "connect", $host, $user, $pass, $database );
	}

	/**
	 *	Returns Micro Time for Time Counter.
	 *	@access		protected
	 *	@return		double
	 */
	protected function getMicroTime()
	{
		$arrTime = explode( " ", microtime() );
		$time = ( doubleval( $arrTime[0] ) + $arrTime[1] ) * 1000;
		return $time;
	}

	/**
	 *	Returns Time Difference between Start and now.
	 *	@access		protected
	 *	@param		double		$start			Start Time
	 *	@return		string
	 */
	protected function getTimeDifference( $start )
	{
		return sprintf( "%1.4f", $this->getMicroTime( true ) - $start );
	}
	
	/**
	 *	Indicates whether Database is connected.
	 *	@access		public
	 *	@return		bool
	 */
	public function isConnected()
	{
		return $this->connected;
	}

	/**
	 *	Sets Log File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@return		void
	 */
	public function setLogFile( $fileName )
	{
		$this->logFile = $fileName;
	}

	/**
	 *	Sets Level of Error Reporting.
	 *	@access		public
	 *	@param		int			$level			Level of Error Reporting (0:none|1:log only|2:log & warning|3:log & error|4:log & exception)
	 */
	public function setErrorReporting( $level )
	{
		$this->errorLevel = $level;
	}
	
	/**
	 *	Handles Error.
	 *	@access		protected
	 *	@param		int			$errorCode		Error Code
	 *	@param		string		$errorMessage	Error Message
	 *	@param		int			$query			Query with Error
	 *	@return		void
	 */
	protected function handleError( $errorCode, $errorMessage, $query )
	{
		if( $this->errorLevel )
		{
			$log = new File_Log_Writer( $this->logFile );
			$log->note( "[".$errorCode.": ".$errorMessage." in EXECUTE (\"".$query."\")]" );
			if( $this->errorLevel == 2 )
				trigger_error( $errorCode.": ".$errorMessage." in EXECUTE (\"".$query."\")", E_USER_WARNING );
			else if( $this->errorLevel == 3 )
				trigger_error( $errorCode.": ".$errorMessage." in EXECUTE (\"".$query."\")", E_USER_ERROR );
			else if( $this->errorLevel == 4 )
				throw new Exception( $errorCode.": ".$errorMessage." in EXECUTE (\"".$query."\")" );
		}
	}
}
?>
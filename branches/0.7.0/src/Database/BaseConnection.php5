<?php
/**
 *	Abstract Database Connection.
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
 *	@package		Database
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Abstract Database Connection.
 *	@category		cmClasses
 *	@package		Database
 *	@abstract
 *	@uses			File_Log_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
abstract class Database_BaseConnection
{
	/**	@var		bool		$connected		State of Connection */
	protected $connected		= FALSE;
	/**	@var		string		$logFile		File Name of Log File */
	protected $logFile			= "db_error.log";
	/**	@var		int			$errorLevel		Level of Error Reporting */
	protected $errorLevel		= 4;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$logFile		FileName of Log File
	 *	@return		void
	 */
	public function __construct( $logFile = FALSE )
	{
		if( $logFile )
			$this->logFile	= $logFile;
		$this->connected = FALSE;
	}

	/**
	 *	Opens a Transaction and sets auto commission.
	 *	@abstract
	 *	@access		public
	 *	@return		void
	 */
	abstract public function beginTransaction();

	/**
	 *	Closes Database Connection.
	 *	@abstract
	 *	@access		public
	 *	@return		void
	 */
	abstract public function close();
	
	/**
	 *	Commits all modifications of Transaction.
	 *	@abstract
	 *	@access		public
	 *	@return		void
	 */
	abstract public function commit();

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
	 *	Establishs persitant Database Connection.
	 *	@access		public
	 *	@param		string		$host			Host Name
	 *	@param		string		$user			User Name
	 *	@param		string		$pass			Password
	 *	@param		string		$database		Database Name
	 *	@return		bool
	 */
	public function connectPersistant( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "pconnect", $host, $user, $pass, $database );
	}

	/**
	 *	Executes SQL Query.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$query			SQL Statement to be executed against Database Connection.
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 */
	abstract public function execute( $query, $debug = 1 );

	protected function getBits( $integer, $length = 8, $reverse = FALSE )
	{
		$bin	= decbin( $integer );
		$bin	= str_pad( $bin, $length, "0", STR_PAD_LEFT );
		$array	= str_split( $bin );
		if( !$reverse )
			$array	= array_reverse( $array );
		return $array;
	}

	/**
	 *	Returns last Error Number.
	 *	@abstract
	 *	@access		public
	 *	@return		int
	 */
	abstract public function getErrNo();

	/**
	 *	Returns last Error.
	 *	@abstract
	 *	@access		public
	 *	@return		string
	 */
	abstract public function getError();

	/**
	 *	Returns last Entry ID.
	 *	@abstract
	 *	@access		public
	 *	@return		int
	 */
	abstract public function getInsertId();
	
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
	 *	Returns List of Tables.
	 *	@abstract
	 *	@access		public
	 *	@return		array
	 */
	abstract public function getTables();

	/**
	 *	Returns Time Difference between Start and now.
	 *	@access		protected
	 *	@param		double		$start			Start Time
	 *	@return		string
	 */
	protected function getTimeDifference( $start )
	{
		return sprintf( "%1.4f", $this->getMicroTime( TRUE ) - $start );
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

	/**
	 *	Cancels Transaction by rolling back all modifications.
	 *	@abstract
	 *	@access		public
	 *	@return		bool
	 */
	abstract public function rollback();

	/**
	 *	Selects a Database on connected Server.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$database		Database Name
	 */
	abstract public function selectDB( $database );

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
	 *	Sets Log File.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Log File
	 *	@return		void
	 */
	public function setLogFile( $fileName )
	{
		$this->logFile = $fileName;
		if( !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
	}
}
?>
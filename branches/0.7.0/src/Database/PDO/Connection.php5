<?php
/**
 *	Enhanced PDO Connection.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		database.pdo
 *	@uses			Exception_SQL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Enhanced PDO Connection.
 *	@category		cmClasses
 *	@package		database.pdo
 *	@uses			Exception_SQL
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.03.2007
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Database_PDO_Connection extends PDO 
{
	public $numberExecutes			= 0;
	public $numberStatements		= 0;
	public $logFileErrors			= NULL;															//  eg. logs/db/pdo/error.log
	public $logFileStatements		= NULL;															//  eg. logs/db/pdo/query.log
	protected $openTransactions		= 0;
	public static $errorTemplate	= "{time}: PDO:{pdoCode} SQL:{sqlCode} {sqlError} ({statement})\n";
	
	/**
	 *	Constructor, establishs Database Connection using a DSN. Set Error Handling to use Exceptions.
	 *	@access		public
	 *	@param		string		$dsn			Data Source Name
	 *	@param		string		$username		Name of Database User
	 *	@param		string		$password		Password of Database User
	 *	@param		array		$driverOptions	Array of Driver Options
	 *	@return		void
	 *	@see		http://php.net/manual/en/pdo.drivers.php
	 */
	public function __construct( $dsn, $username = NULL, $password = NULL, $driverOptions = array() )
	{
		parent::__construct( $dsn, $username, $password, $driverOptions );
		$this->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}

	/**
	 *	Starts a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function beginTransaction()
	{
		$this->openTransactions++;													//  increase Transaction Counter
		if( $this->openTransactions == 1)											//  no Transaction is open
			parent::beginTransaction();												//  begin Transaction
		return TRUE;
	}

	/**
	 *	Commits a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function commit()
	{
		if( !$this->openTransactions )												//  there has been an inner RollBack or no Transaction was opened
			return FALSE;															//  ignore Commit
		if( $this->openTransactions == 1)											//  commit of an outer Transaction
			parent::commit();														//  commit Transaction
		$this->openTransactions--;													//  decrease Transaction Counter
		return TRUE;	
	}

	/**
	 *	Executes a Statement and returns Number of affected Rows.
	 *	@access		public
	 *	@param		string		$statement			SQL Statement to execute
	 *	@return		int
	 */
	public function exec( $statement )
	{
		$this->logStatement( $statement );
		try
		{
			$this->numberExecutes++;
			$this->numberStatements++;
			return parent::exec( $statement );
		}
		catch( PDOException $e )
		{
			$this->logError( $e, $statement );										//  logs Error and throws SQL Exception
		}
	}
	
	/**
	 *	Notes Information from PDO Exception in Error Log File and throw SQL Exception.
	 *	@access		protected
	 *	@param		PDOException	$e				PDO Exception thrown by invalid SQL Statement
	 *	@param		string			$statement		SQL Statement which originated PDO Exception
	 *	@return		void
	 */
	protected function logError( Exception $exception, $statement )
	{
		if( !$this->logFileErrors )
			return;
		$info		= $exception->errorInfo;
		$sqlError	= $info[2];
		$sqlCode	= $info[1];
		$pdoCode	= $info[0];
		$message	= $exception->getMessage();
		$statement	= preg_replace( "@\r?\n@", " ", $statement );	
		$statement	= preg_replace( "@  +@", " ", $statement );	
		
		$note	= self::$errorTemplate;
		$note	= str_replace( "{time}", time(), $note );
		$note	= str_replace( "{sqlError}", $sqlError, $note );
		$note	= str_replace( "{sqlCode}", $sqlCode, $note );
		$note	= str_replace( "{pdoCode}", $pdoCode, $note );
		$note	= str_replace( "{message}", $message, $note );
		$note	= str_replace( "{statement}", $statement, $note );
				
		error_log( $note, 3, $this->logFileErrors );
		import( 'de.ceus-media.exception.SQL' );
		throw new Exception_SQL( $info[2], $info[1], $info[0] );
	}

	/**
	 *	Notes a SQL Statement in Statement Log File.
	 *	@access		protected
	 *	@param		string		$statement		SQL Statement
	 *	@return		void
	 */
	protected function logStatement( $statement )
	{
		if( !$this->logFileStatements )
			return;
		$statement	= preg_replace( "@(\r)?\n@", " ", $statement );
		$message	= time()." ".getEnv( 'REMOTE_ADDR' )." ".$statement."\n";
		error_log( $message, 3, $this->logFileStatements);
	}
	
	public function prepare( $statement, $driverOptions = array() )
	{
		$this->numberStatements++;
		$this->logStatement( $statement );
		return parent::prepare( $statement, $driverOptions );
	}

	public function query( $statement, $fetchMode = PDO::FETCH_ASSOC )
	{
		$this->logStatement( $statement );
		$this->numberStatements++;
		try
		{
			return parent::query( $statement, $fetchMode );
		}
		catch( PDOException $e )
		{
			$this->logError( $e, $statement );										//  logs Error and throws SQL Exception
		}
	}

	/**
	 *	Rolls back a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function rollBack()
	{
		if( !$this->openTransactions )												//  there has been an inner RollBack or no Transaction was opened
			return FALSE;															//  ignore Commit
		parent::rollBack();															//  roll back Transaction
		$this->openTransactions = 0;												//  reset Transaction Counter
		return TRUE;
	}

	/**
	 *	Sets File Name of Error Log.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Statement Error File
	 *	@return		void
	 */
	public function setErrorLogFile( $fileName )
	{
		$this->logFileErrors	= $fileName;
		if( $fileName && !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
	}

	/**
	 *	Alias for setErrorLogFile.
	 *	@deprecated		in 0.7
	 */
	public function setLogFile( $file )
	{
		return $this->setErrorLogFile( $file );
	}

	/**
	 *	Alias for setStatementLogFile.
	 *	@deprecated		in 0.7
	 */
	public function setQueryLogFile( $file )
	{
		return $this->setStatementLogFile( $file );
	}

	/**
	 *	Sets File Name of Statement Log.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Statement Log File
	 *	@return		void
	 */
	public function setStatementLogFile( $fileName )
	{
		$this->logFileStatements	= $fileName;
		if( $fileName && !file_exists( dirname( $fileName ) ) )
			mkDir( dirname( $fileName ), 0700, TRUE );
	}
}
?>
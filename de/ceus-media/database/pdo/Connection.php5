<?php
/**
 *	Enhanced PDO Connection.
 *	@package		database.pdo
 *	@uses			Exception_SQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Enhanced PDO Connection.
 *	@package		database.pdo
 *	@uses			Exception_SQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Database_PDO_Connection extends PDO 
{
	public $numberExecutes			= 0;
	public $numberStatements		= 0;
	public $logFileErrors			= "logs/db_error.log";
	public $logFileStatements		= "logs/queries.log";
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
	public function __construct( $dsn, $username, $password, $driverOptions = array() )
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
		if( $this->logFileStatements )
		{
			$statement	= preg_replace( "@(\r)?\n@", " ", $statement );
			$message	= time()." ".getEnv( 'REMOTE_ADDR' )." ".$statement."\n";
			error_log( $message, 3, $this->logFileStatements);
		}
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
	}
}
?>
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
	public $numberExecutes		= 0;
	public $numberStatements	= 0;
	public $logFileErrors		= "logs/db_error.log";
	public $logFileStatements	= "logs/queries.log";
	protected $openTransactions	= 0;
	
	
	public function setLogFile( $file )
	{
		return $this->setErrorLogFile( $file );
	}

	public function setQueryLogFile( $file )
	{
		return $this->setStatementLogFile( $file );
	}

	/**
	 *	Starts a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function beginTransaction()
	{
		$this->openTransactions++;						//  increase Transaction Counter
		if( $this->openTransactions == 1)				//  no Transaction is open
			parent::beginTransaction();					//  begin Transaction
		return TRUE;
	}

	/**
	 *	Commits a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function commit()
	{
		if( !$this->openTransactions )					//  there has been an inner RollBack or no Transaction was opened
			return FALSE;								//  ignore Commit
		if( $this->openTransactions == 1)				//  commit of an outer Transaction
			parent::commit();							//  commit Transaction
		$this->openTransactions--;						//  decrease Transaction Counter
		return TRUE;
	}

	/**
	 *	Rolls back a Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function rollBack()
	{
		if( !$this->openTransactions )					//  there has been an inner RollBack or no Transaction was opened
			return FALSE;								//  ignore Commit
		parent::rollBack();								//  roll back Transaction
		$this->openTransactions = 0;					//  reset Transaction Counter
		return TRUE;
	}

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
			$this->logError( $e, $statement );						//  logs Error and throws SQL Exception
		}
	}
	
	protected function logError( Exception $e, $statement )
	{
		$info	= $this->errorInfo();
		error_log( time().":".$e->getMessage()." -> ".$statement."\n", 3, $this->logFileErrors );
		import( 'de.ceus-media.exception.SQL' );
		throw new Exception_SQL( $info[1], $info[2], $info[0] );
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
			$this->logError( $e, $statement );						//  logs Error and throws SQL Exception
		}
	}

	private function logStatement( $statement )
	{
		if( $this->logFileStatements )
		{
			$statement	= preg_replace( "@(\r)?\n@", " ", $statement );
			$message	= time()." ".getEnv( 'REMOTE_ADDR' )." ".$statement."\n";
			error_log( $message, 3, $this->logFileStatements);
		}
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
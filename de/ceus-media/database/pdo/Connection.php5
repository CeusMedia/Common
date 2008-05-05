<?php
import( 'de.ceus-media.database.pdo.Statement' );
import( 'de.ceus-media.exception.SQL' );
/**
 *	Enhanced PDO Connection.
 *	@package		database.pdo
 *	@uses			Database_PDO_Statement
 *	@uses			Exception_SQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 */
/**
 *	Enhanced PDO Connection.
 *	@package		database.pdo
 *	@uses			Database_PDO_Statement
 *	@uses			Exception_SQL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			09.03.2007
 *	@version		0.1
 *	@todo			Code Documentation
 */
class Database_PDO_Connection
{
	private $cwd;
	protected $PDO;
	public $numExecutes;
	public $numStatements;
	protected $logFile		= "logs/db_error.log";
	private $queries		= array();
	public $queryLogFile	= "logs/queries.log";
	
	public function __construct( $dsn, $user = NULL, $pass = NULL, $driver_options = NULL )
	{
		$this->PDO = new PDO( $dsn, $user, $pass, $driver_options );
		$this->numExecutes = 0;
		$this->numStatements = 0;
		$this->cwd	= getCwd();
	}

	public function __destruct()
	{
		$this->PDO	= null;
	}

	public function __call( $func, $args )
	{
		return call_user_func_array( array( &$this->PDO, $func ), $args );
	}


public function beginTransaction()
{
	if( !$this->openTransactions )					//  no Transaction is open
		parent::beginTransaction();					//  begin Transaction
	$this->openTransactions++;						//  increase Transaction Counter
	return true;
}

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
	 *	Rolls back an Transaction.
	 *	@access		public
	 *	@return		bool
	 */
	public function rollBack()
	{
		if( !$this->openTransactions )					//  there has been an inner RollBack or no Transaction was opened
			return FALSE;								//  ignore Commit
		parent::rollBack();								//  roll back Transaction
		$this->openTransactions = 0;					//  reset Transaction Counter
}

	public function exec( $query, $verbose = 1 )
	{
		$this->logQuery( $query );
		try
		{
			$this->numExecutes++;
			return call_user_func_array( array( &$this->PDO, 'exec' ), array( $query ) );
		}
		catch( PDOException $e )
		{
			$this->logError( $e );
			return false;
		}
	}
	
	protected function logError( Exception $e )
	{
		$info	= $this->errorInfo();
		error_log( time().":".$e->getMessage()."\n", 3, $this->logFile );
		throw new Exception_SQL( $info[1], $info[2], $info[0] );
	}
	
	public function prepare()
	{
		$this->numStatements++;
		$args = func_get_args();
		$PDOS = call_user_func_array( array( &$this->PDO, 'prepare' ), $args );
		return new Database_PDO_Statement( $this, $PDOS );
	}

	public function query( $query, $verbose = 1, $fetchMode = PDO::FETCH_ASSOC )
	{
		$this->logQuery( $query );
		try
		{
			$this->numExecutes++;
			if( $verbose )
			{
				if( $verbose == 2 )
					echo $query;
				if( $verbose == 4 )
					remark( $query );
				if( $verbose == 5 )
					die( $query );
			}
			$PDOS = call_user_func_array( array( &$this->PDO, 'query' ), array( $query, $fetchMode ) );
			return new Database_PDO_Statement( $this, $PDOS );
		}
		catch( PDOException $e )
		{
			$this->logError( $e );
			return false;
		}
	}

	private function logQuery( $query )
	{
		if( $this->queryLogFile )
			error_log( $query."\n".str_repeat( "-", 80 )."\n", 3, $this->queryLogFile );
	}

	public function setLogFile( $fileName )
	{
		$this->logFile	= $fileName;
	}

	public function setQueryLogFile( $fileName )
	{
		$this->queryLogFile	= $fileName;
	}
}
?>
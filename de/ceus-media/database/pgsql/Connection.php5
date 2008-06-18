<?php
import( 'de.ceus-media.database.BaseConnection' );
import( 'de.ceus-media.database.Result' );
import( 'de.ceus-media.database.Row' );
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
 *	@package		database.mysql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 */
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
 *	@package		database.mysql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.6
 *	@todo			Code Documentation
 */
class Database_pgSQL_Connection extends Database_BaseConnection
{
	/**	@var		double		$countTime			Counter of Query Times */	
	public $countTime;
	/**	@var		int			$countQueries		Counter of Queries */	
	public $countQueries;
	/**	@var		string		$data				Name of currently selected Database */	
	protected $database;
	/**	@var		resource	$dbc				Database Connection Resource */	
	protected $dbc;
	/**	@var		int			$insertId			ID of latest inserted Table Entry */	
	protected $insertId;
	/**	@var		int			$openTransactions	Counter for open Transactions */	
	protected $openTransactions = 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		logfile		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $logfile	= false )
	{
		parent::__construct( $logfile );
		$this->insertId = false;
	}

	/**
	 *	Opens a Transaction and sets auto commission.
	 *	@access		public
	 *	@return		void
	 */
	public function beginTransaction()
	{
		$this->openTransactions ++;
		if( $this->openTransactions == 1 )
		{
			$query = "BEGIN";
			$this->Execute ($query);
		}
	}

	/**
	 *	Closes Database Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function close()
	{
		pg_close( $this->dbc );
	}
	
	/**
	 *	Commits all modifications of Transaction.
	 *	@access		public
	 *	@return		void
	 */
	public function commit()
	{
		if( $this->openTransactions == 1 )
		{
			$query = "COMMIT";
			$this->Execute( $query );
		}
		$this->openTransactions--;
		if( $this->openTransactions < 0 )
			$this->openTransactions = 0;
	}

	public function connectDatabase( $type, $host, $user, $pass, $database = false )
	{
		if( $type == "connect" )
		{
			$resource	= pg_connect( "host=".$host." dbname=".$database." user=".$user." password=".$pass );
			if( !$resource )
				throw new Exception( 'Database Connection failed for User "'.$user.'" on Host "'.$host.'".' );
			$this->dbc = $resource;
			return $this->connected = true;
		}
		else if( $type == "pconnect" )
		{
			$resource	= pg_pconnect( $host, $user, $pass );
			if( !$resource )
				throw new Exception( 'Database Connection failed for User "'.$user.'" on Host "'.$host.'".' );
			$this->dbc = $resource;
			if( $database )
				if( $this->selectDB( $database ) )
					return $this->connected = true;
		}
		return false;
	}

	/**
	 *	Executes SQL Query.
	 *	@param	string	query			SQL Statement to be executed against Database Connection.
	 *	@param	int		debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 */
	public function execute( $query, $debug = 1 )
	{
		$result = false;
		if( $query )
		{
			if( $debug > 0 )
			{
				$bits = $this->getBits( $debug, 5 );
				if( $bits[0] )
				{
					$this->countQueries++;
					$start = $this->getMicroTime();
				}
				if( $bits[1] )
					echo $query;
				if( $bits[2] )
					remark( $query );
				if( $bits[3] )
					die();
			}
			if( eregi( "^( |\n|\r|\t)*(INSERT)", $query ) )
			{
				if( $result = pg_query( $this->dbc, $query ) )
				{
					$this->insertId = (int) pg_last_oid( $result );
					$result	= $this->insertId;
				}
			}
			else if( eregi( "^( |\n|\r|\t)*(SELECT|SHOW)", $query ) )
			{
				$result = new Database_Result();
				if( $q = pg_query( $this->dbc, $query ) )
				{
					while( $d = pg_fetch_array( $q ) )
					{
						$row = new Database_Row();
						foreach( $d as $key => $value )
							$row->$key = $value;
						$result->rows[] = $row;
					}
				}
			}
			else
			{
				$result = pg_query( $this->dbc, $query );
			}
			if( pg_last_error() )
				$this->handleError( 0/*pg_errno()*/, pg_last_error(), $query );
			if( $debug > 0 )
			{
				if( $bits[0] )
					$this->countTime += $this->getTimeDifference( $start );
				if( $bits[4] )
					die();
			}
			return $result;
		}
	}

	public function getAffectedRows()
	{
		return pg_affected_rows();
	}

	public function getDatabases()
	{
		$db_list = pg_list_dbs( $this->dbc );
		$databases	= array();
		while( $row = pg_fetch_object( $db_list ) )
			$databases[]	= $row->Database . "\n";
		return $databases;
	}

	/**
	 *	Returns last Error Number.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrNo()
	{
#		return pg_errno( $this->dbc );
	}

	/**
	 *	Returns last Error.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		return pg_last_error( $this->dbc );
	
	}

	/**
	 *	Returns last Entry ID.
	 *	@access		public
	 *	@return		int
	 */
	public function getInsertId()
	{
		return $this->insertId;
	}
	
	public function getTables()
	{
		$tab_list = pg_list_tables( $this->database, $this->dbc );
		while( $table	= pg_fetch_row( $tab_list ) )
			$tables[]	= $table['0'];
		return $tables;
	}

	/**
	 *	Cancels Transaction by rolling back all modifications.
	 *	@access		public
	 *	@return		bool
	 */
	public function rollback()
	{
		if( $this->openTransactions == 0 )
			return false;
		$query = "ROLLBACK";
		$this->Execute( $query );
		$this->openTransactions = 0;
		return true;
	}

	public function selectDB( $database )
	{
		if( $this->Execute( "use ".$database ) )
		{
			$this->database = $database;
			return true;
		}
		return false;
	}
}
?>
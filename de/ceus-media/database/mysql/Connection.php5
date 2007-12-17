<?php
import( 'de.ceus-media.database.BaseConnection' );
import( 'de.ceus-media.database.mysql.Result' );
import( 'de.ceus-media.database.mysql.Row' );
import( 'de.ceus-media.functions.getBits' );
/**
 *	MySQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		Database_BaseConnection
 *	@uses			Database_MySQL_Result
 *	@uses			Database_MySQL_Row
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.5
 */
/**
 *	MySQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		Database_BaseConnection
 *	@uses			Database_MySQL_Result
 *	@uses			Database_MySQL_Row
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.5
 *	@todo			Code Documentation
 */
class Database_MySQL_Connection extends Database_BaseConnection
{
	protected $dbc;
	protected $database;
	protected $insertId;
	public $countTime;
	public $countQueries;

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
	 *	Closes Database Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function close()
	{
		mysql_close( $this->dbc );
	}
	
	/**
	 *	Returns last Error.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		return mysql_error( $this->dbc );
	
	}

	/**
	 *	Closes Database Connection.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrNo()
	{
		return mysql_errno( $this->dbc );
	}

	public function connect( $host, $user, $pass, $database = false, $verbose = false )
	{
		if( $verbose )
			return $this->connectDatabase( "connect", $host, $user, $pass, $database );
		return @$this->connectDatabase( "connect", $host, $user, $pass, $database );
	}

	public function connectDatabase( $type, $host, $user, $pass, $database = false )
	{
		if( $type == "connect" )
		{
			if( $this->dbc = mysql_connect( $host, $user, $pass ) )
				if( $database )
					if( $this->selectDB( $database ) )
						return $this->connected = true;
		}
		else if( $type == "pconnect" )
		{
			if( $this->dbc = mysql_pconnect( $host, $user, $pass ) )
			{
				if( $database )
					if( $this->selectDB( $database ) )
						return $this->connected = true;
			}
		}
		return false;
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
				$bits = getBits( $debug, 5 );
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
			if (eregi( "^( |\n|\r|\t)*(INSERT)", $query ) )
			{
				if( mysql_query( $query, $this->dbc ) )
				{
					$this->insertId = (int) mysqlinsertId( $this->dbc );
					$result	= $this->insertId;
				}
			}
			else if( eregi( "^( |\n|\r|\t)*(SELECT|SHOW)", $query ) )
			{
				$result = new Database_MySQL_Result();
				if( $q = mysql_query( $query, $this->dbc ) )
				{
					while( $d = mysql_fetch_array( $q ) )
					{
						$row = new Database_MySQL_Row();
						foreach( $d as $key => $value )
							$row->$key = $value;
						$result->objects[] = $row;
					}
				}
			}
			else
			{
				$result = mysql_query( $query, $this->dbc );
			}
			if( mysql_errno() )
				$this->handleError( mysql_errno(), mysql_error(), $query );
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

	public function getInsertId()
	{
		return $this->insertId;
	}
	
	public function getDatabases()
	{
		$db_list = mysql_list_dbs( $this->dbc );
		$databases	= array();
		while( $row = mysql_fetch_object( $db_list ) )
			$databases[]	= $row->Database . "\n";
		return $databases;
	}

	public function getTables()
	{
		$tab_list = mysql_list_tables( $this->database, $this->dbc );
		while( $table	= mysql_fetch_row( $tab_list ) )
			$tables[]	= $table['0'];
		return $tables;
	}

	public function connectPersistant( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "pconnect", $host, $user, $pass, $database );
	}

	public function getAffectedRows()
	{
		return mysql_affected_rows();
	}
}
?>
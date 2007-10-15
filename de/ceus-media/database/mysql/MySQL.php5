<?php
import( 'de.ceus-media.database.BaseConnection' );
import( 'de.ceus-media.database.mysql.MySQLResult' );
import( 'de.ceus-media.database.mysql.MySQLRow' );
import( 'de.ceus-media.functions.getBits' );
/**
 *	MySQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		BaseConnection
 *	@uses			MySQLResult
 *	@uses			MySQLRow
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 */
/**
 *	MySQL Connection
 *
 *	Is a mySQL Wrapper class for bypassing AdoDB to reach better performance.
 *	Most important functions of AdoDB API are realised.
 *
 *	@package		database
 *	@extends		BaseConnection
 *	@uses			MySQLResult
 *	@uses			MySQLRow
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version 		0.4
 *	@todo			Dokumentation beenden
 */
class MySQL extends BaseConnection
{
	var $_dbc;
	var $_database;
	var $_insert_id;
	var $countTime;
	var $countQueries;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		logfile		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $logfile	= false )
	{
		parent::__construct( $logfile );
		$this->_insert_id = false;
	}

	function close()
	{
		mysql_close( $this->_dbc );
	}
	
	function getError()
	{
		return mysql_error( $this->_dbc );
	
	}

	function getErrNo()
	{
		return mysql_errno( $this->_dbc );
	
	}

	function connect( $host, $user, $pass, $database = false, $verbose = false )
	{
		if( $verbose )
			return $this->connectDatabase( "connect", $host, $user, $pass, $database );
		return @$this->connectDatabase( "connect", $host, $user, $pass, $database );
	}

	function connectDatabase( $type, $host, $user, $pass, $database = false )
	{
		if( $type == "connect" )
		{
			if( $this->_dbc = mysql_connect( $host, $user, $pass ) )
				if( $database )
					if( $this->selectDB( $database ) )
						return $this->_connected = true;
		}
		else if( $type == "pconnect" )
		{
			if( $this->_dbc = mysql_pconnect( $host, $user, $pass ) )
			{
				if( $database )
					if( $this->selectDB( $database ) )
						return $this->_connected = true;
			}
		}
		return false;
	}

	function selectDB( $database )
	{
		if( $this->Execute( "use ".$database ) )
		{
			$this->_database = $database;
			return true;
		}
		return false;
	}

	/**
	*	@param	string	query			SQL Statement to be executed against Database Connection.
	 *	@param	int		debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 */
	function execute( $query, $debug = 1 )
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
				if( mysql_query( $query, $this->_dbc ) )
				{
					$this->_insert_id = (int) mysql_insert_id( $this->_dbc );
					$result	= $this->_insert_id;
				}
			}
			else if( eregi( "^( |\n|\r|\t)*(SELECT|SHOW)", $query ) )
			{
				$result = new MySQLResult();
				if( $q = mysql_query( $query, $this->_dbc ) )
				{
					while( $d = mysql_fetch_array( $q ) )
					{
						$row = new MySQLRow();
						foreach( $d as $key => $value )
							$row->$key = $value;
						$result->objects[] = $row;
					}
				}
			}
			else
			{
				$result = mysql_query( $query, $this->_dbc );
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

	function Insert_ID()
	{
		return $this->_insert_id;
	}

	function InsertId()
	{
		return $this->_insert_id;
	}
	
	function MetaDatabases()
	{
		$db_list = mysql_list_dbs( $this->_dbc );
		$databases	= array();
		while( $row = mysql_fetch_object( $db_list ) )
			$databases[]	= $row->Database . "\n";
		return $databases;
	}

	function MetaTables()
	{
		$tab_list = mysql_list_tables( $this->_database, $this->_dbc );
		while( $table	= mysql_fetch_row( $tab_list ) )
			$tables[]	= $table['0'];
		return $tables;
	}

	function PConnect( $host, $user, $pass, $database )
	{
		return $this->connectDatabase( "pconnect", $host, $user, $pass, $database );
	}

	function affectedRows()
	{
		return mysql_affected_rows();
	}

	function Affected_Rows()
	{
		return $this->affectedRows();
	}
}
?>
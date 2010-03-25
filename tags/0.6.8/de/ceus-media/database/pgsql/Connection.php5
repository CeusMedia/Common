<?php
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
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
 *	@package		database.pgsql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.database.BaseConnection' );
import( 'de.ceus-media.database.Result' );
import( 'de.ceus-media.database.Row' );
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
 *	@category		cmClasses
 *	@package		database.pgsql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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
	 *	@param		string		$logFile		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $logFile = FALSE )
	{
		parent::__construct( $logFile );
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
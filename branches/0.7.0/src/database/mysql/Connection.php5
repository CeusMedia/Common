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
 *	@package		database.mysql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version 		$Id$
 */
import( 'de.ceus-media.database.BaseConnection' );
import( 'de.ceus-media.database.Result' );
import( 'de.ceus-media.database.Row' );
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
 *	@category		cmClasses
 *	@package		database.mysql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version 		$Id$
 *	@todo			Code Documentation
 */
class Database_mySQL_Connection extends Database_BaseConnection
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

	public static $autoCommit	= 1;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$logFile		File Name of Log File
	 *	@return		void
	 */
	public function __construct( $logFile = FALSE )
	{
		parent::__construct( $logFile );
		$this->insertId	= FALSE;
	}

	/**
	 *	Opens a Transaction and sets auto commission.
	 *	@access		public
	 *	@return		void
	 */
	public function beginTransaction()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );

		$this->openTransactions ++;
		if( $this->openTransactions == 1 )
		{
			if( self::$autoCommit )
				$this->execute( 'SET AUTOCOMMIT=0' );
			$this->execute( 'START TRANSACTION' );
		}
	}

	/**
	 *	Closes Database Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function close()
	{
		if( $this->dbc )
			mysql_close( $this->dbc );
	}
	
	/**
	 *	Commits all modifications of Transaction.
	 *	@access		public
	 *	@return		void
	 */
	public function commit()
	{
		if( $this->openTransactions < 1 )
			throw new RuntimeException( 'Transaction not opened' );

		if( $this->openTransactions == 1 )
		{
			$this->execute( 'COMMIT' );
			$this->openTransactions--;
			if( self::$autoCommit )
				$this->execute( 'SET AUTOCOMMIT=1' );
		}
	}

	/**
	 *	Sets up a Database Connection.
	 *	@access		public
	 *	@param		string		$type		Connection Type (connect|pconnect)
	 *	@param		string		$host		Database Host Name
	 *	@param		string		$user		Database User Name
	 *	@param		string		$pass		Database User Password
	 *	@param		string		$database	Database to select
	 *	@return		bool		Flag: Database Connection established
	 */
	public function connectDatabase( $type, $host, $user, $pass, $database = NULL )
	{
		switch( $type )
		{
			case 'connect':
				$resource	= mysql_connect( $host, $user, $pass );
				break;
			case 'pconnect':
				$resource	= mysql_pconnect( $host, $user, $pass );
				break;
			default:
				throw new InvalidArgumentException( 'Database Connection Type "'.$type.'" is invalid' );
		}
		if( !$resource )
			throw new Exception( 'Database Connection failed for User "'.$user.'@'.$host.'"' );
		$this->dbc			= $resource;
		if( $database )
			return $this->selectDB( $database );
		return TRUE;
	}

	/**
	 *	Executes SQL Query.
	 *	@param	string	query			SQL Statement to be executed against Database Connection.
	 *	@param	int		debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 */
	public function execute( $query, $debug = 1 )
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );

		$result = FALSE;
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
			if( eregi( '^( |\n|\r|\t)*(INSERT)', $query ) )
			{
				if( mysql_query( $query, $this->dbc ) )
				{
					$this->insertId = (int) mysql_insert_Id( $this->dbc );
					$result	= $this->insertId;
				}
			}
			else if( eregi( '^( |\n|\r|\t)*(SELECT|SHOW)', $query ) )
			{
				$result = new Database_Result();
				if( $q = mysql_query( $query, $this->dbc ) )
				{
					while( $d = mysql_fetch_array( $q ) )
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

	public function getAffectedRows()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );
		return mysql_affected_rows();
	}

	public function getDatabases()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );

		$list		= array();
		$databases	= mysql_list_dbs( $this->dbc );
		while( $row = mysql_fetch_object( $databases ) )
			$list[]	= $row->Database . "\n";
		return $list;
	}

	/**
	 *	Returns last Error Number.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrNo()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );
		return mysql_errno( $this->dbc );
	}

	/**
	 *	Returns last Error.
	 *	@access		public
	 *	@return		string
	 */
	public function getError()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );
		return mysql_error( $this->dbc );
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
		if( !$this->dbc )
			throw new RuntimeException( 'Database not conntected' );
		$tab_list = mysql_list_tables( $this->database, $this->dbc );
		while( $table	= mysql_fetch_row( $tab_list ) )
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
		if( $this->openTransactions < 1 )
			throw new RuntimeException( 'Transaction not opened' );

		if( $this->openTransactions == 1 )
		{
			$this->execute( 'ROLLBACK' );
			if( self::$autoCommit )
				$this->execute( 'SET AUTOCOMMIT=1' );
		}
		$this->openTransactions--;
		return TRUE;
	}

	public function selectDB( $database )
	{
		if( !$this->execute( 'use '.$database ) )
			return FALSE;
		$this->database	= $database;
		return TRUE;
	}
}
?>
<?php
/**
 *	Wrapper for mySQL Database Connection with Transaction Support.
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
 *	@package		database.mysql
 *	@extends		Database_BaseConnection
 *	@uses			Database_Result
 *	@uses			Database_Row
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version 		0.6
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
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version 		0.6
 *	@todo			Code Documentation
 */
class Database_Mysql_LazyConnection extends Database_Mysql_Connection
{
	protected $dataForConnectOnDemand		= array();

	/**
	 *	Opens a Transaction.
	 *	@access		public
	 *	@return		void
	 */
	public function beginTransaction()
	{
		if( !$this->dbc )
			$this->openConnection();
		parent::beginTransaction();
	}

	public function connectDatabase( $type, $host, $user, $pass, $database = NULL )
	{
		$this->dataForConnectOnDemand	= array(
			'type'		=> $type,
			'host'		=> $host,
			'user'		=> $user,
			'pass'		=> $pass,
			'database'	=> $database,
		);
	}

	protected function openConnection()
	{
		if( !$this->dataForConnectOnDemand )
			throw new RuntimeException( 'Database not connected' );
		extract( $this->dataForConnectOnDemand );
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
			$this->selectDB( $database );
	}

	/**
	 *	Executes SQL Query.
	 *	@param	string	query			SQL Statement to be executed against Database Connection.
	 *	@param	int		debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 */
	public function execute( $query, $debug = 1 )
	{
		if( !$this->dbc )
			$this->openConnection();
		return parent::execute( $query, $debug );
	}

	public function getAffectedRows()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database has not been used' );
		return parent::getAffectedRows();
	}

	public function getDatabases()
	{
		if( !$this->dbc )
			$this->openConnection();
		return parent::getDatabases();
	}

	/**
	 *	Returns last Error Number.
	 *	@access		public
	 *	@return		int
	 */
	public function getErrNo()
	{
		if( !$this->dbc )
			throw new RuntimeException( 'Database has not been used' );
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
			throw new RuntimeException( 'Database has not been used' );
		return mysql_error( $this->dbc );
	}
	
	public function getTables()
	{
		if( !$this->dbc )
			$this->openConnection();
		if( !$this->database )
			throw new RuntimeException( 'No Database selected' );
		return parent::getTables();
	}

	public function selectDB( $database )
	{
		if( !$this->dbc )
			$this->openConnection();
		return parent::selectDB( $database );
	}
}
?>
<?php
/**
 *	Write Access for Database Tables.
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
 *	@package		Database.PDO
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Write Access for Database Tables.
 *	@category		cmClasses
 *	@package		Database.PDO
 *	@extends		Database_PDO_TableReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */

class Database_PDO_TableWriter extends Database_PDO_TableReader
{

	/**
	 *	Deletes focused Rows in this Table and returns number of affected Rows.
	 *	@access		public
	 *	@return		int
	 */

	public function delete( )
	{
		$this->validateFocus( );
		$conditions = $this->getConditionQuery( array( ) );
		$this->getTableLinks( $conditions );
		//saeid	{
		global $sw_log_edit;
		if ( $sw_log_edit )
		{
			global $log_edit;
			$dataP[ 'TableName' ] = $this->getTables( );
			$query = 'SELECT * FROM ' . $this->getTables( ) . ' WHERE ' . $conditions;
			$resultSet = $this->dbc->query( $query );
			if ( $resultSet )
				$dataP[ 'data' ] = $resultSet->fetchAll( $this->getFetchMode( ) );
			else
				$dataP[ 'data' ] = array( );
			$dataP[ 'columns' ] = $this->columns;
			$dataP[ 'mode' ] = 'delete';
			array_push( $log_edit, $dataP );
		}
		//}	

		$query = 'DELETE FROM ' . $this->getTables( FALSE ) . ' WHERE ' . $conditions;
		#		$has	= $this->get( FALSE );
		#		if( !$has )
		#			throw new InvalidArgumentException( 'Focused Indices are not existing.' );
		return $this->dbc->exec( $query );
	}

	/**
	 *	Deletes data by given conditions.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@return		bool
	 */

	public function deleteByConditions( $where = array( ) )
	{
		$conditions = $this->getConditionQuery( $where );
		$this->getTableLinks( $conditions );
		//saeid	{
		global $sw_log_edit;
		if ( $sw_log_edit )
		{
			global $log_edit;
			$dataP[ 'TableName' ] = $this->getTables( );
			$query = 'SELECT * FROM ' . $this->getTables( ) . ' WHERE ' . $conditions;
			$resultSet = $this->dbc->query( $query );
			if ( $resultSet )
				$dataP[ 'data' ] = $resultSet->fetchAll( $this->getFetchMode( ) );
			else
				$dataP[ 'data' ] = array( );
			$dataP[ 'columns' ] = $this->columns;
			$dataP[ 'mode' ] = 'deleteByConditions';
			array_push( $log_edit, $dataP );
		}
		//}	
		$query = 'DELETE ' . $this->getTables( FALSE ) . ' FROM ' . $this->getTables( FALSE ) . ' WHERE ' . $conditions;
		$result = $this->dbc->exec( $query );
		$this->defocus( );
		return $result;
	}

	/**
	 *	Inserts data into this table and returns ID.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from values
	 *	@return		int			ID of inserted row
	 */

	public function insert( $data = array( ), $stripTags = TRUE )
	{

		//saeid	{
		global $sw_log_edit;
		global $log_edit;
		if ( $sw_log_edit )
		{
			$dataP[ 'TableName' ] = $this->getTableName( );
			$dataP[ 'data' ] = $data;
			$dataP[ 'columns' ] = $this->columns;
			$dataP[ 'mode' ] = 'insert';
		}
		//}
		$dataInput = $data;
		$alias = $this->getAlias( );
		if ( $alias )
			$this->correctDataToAlias( $data );
		$columns = array( );
		$values = array( );
		foreach ( $this->columns as $column ) //  iterate Columns
		{
			$column = str_replace( $alias . '.', '', $column );
			if ( !isset( $data[ $column ] ) ) //  no Data given for Column
				continue;
			$value = $data[ $column ];
			if ( $stripTags )
				$value = strip_tags( $value );
			$columns[ $column ] = '`' . $column . '`';
			$values[ $column ] = $this->secureValue( $value );
		}
		if ( $this->isFocused( ) ) //  add focused indices to data
		{
			//saeid:{ for double fuckus
			foreach ( $this->focusedIndices as $index => $value ) //  iterate focused indices
			{	
				if ( isset( $columns[ $value[ 0 ] ] ) ) //  Column is already set
					continue;
				if ( $value[ 0 ] == $this->primaryKey ) //  skip primary key
					continue;
				if( !strstr( substr( $value[ 0 ], 0, strlen( $alias . '.' ) ), $alias . '.' ) ){ //wenn Focus für Join ist ,halt es in $Data und schiekt es zu nächste Objekt 
					$data[ $value[ 0 ]] =  $value[ 1 ];
				 	continue;	
				}	
				$columns[ $value[ 0 ] ] = '`' . str_replace( $alias . '.', '', $value[ 0 ] ) . '`'; //  add key
				$values[ $value[ 0 ] ] = $this->secureValue( $value[ 1 ] ); //  add value
			}
			//}
		}
		$columns = implode( ', ', array_values( $columns ) );
		$values = implode( ', ', array_values( $values ) );
		$query = 'INSERT INTO ' . $this->getTableName( ) . ' (' . $columns . ') VALUES (' . $values . ')';
		$this->dbc->exec( $query );
		$id = $this->dbc->lastInsertId( );
		if ( $this->isJoin( ) )
		{
			foreach ( $this->JoinList as $Join )
			{
				$alias = $Join['ObjTableReader']->getAlias(true);
				$columnLink = array( str_replace( $alias . '.', '', $Join[ 'TableLink' ][1]) => $id );
				$Join[ 'ObjTableReader' ]->insert( array_merge( $columnLink,$data ) );
			}
		}
		//saeid {
		if ( $sw_log_edit )
		{
			$dataP[ 'id' ] = $id;
			array_push( $log_edit, $dataP );
		}
		//}
		return $id;
	}

	/**
	 * 
	 * @param 		array 		$data		associative array of data to store
	 */
	private function correctDataToAlias( &$data )
	{
		$alias = $this->getAlias( );
		$dataCorrect = array( );
		foreach ( $data as $key => $value )
		{
			if ( stripos( $key, '.' ) )
			{
				$dataCorrect[ str_replace( $alias . '.', '', $key ) ] = $value;
			}
			else
				$dataCorrect[ $key ] = $value;
		}
		$data = $dataCorrect;
	}
	
	/**
	 *	Updating data of focused primary key in this table.
	 *	@access		public
	 *	@param		array		$data			Map of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML tags from values
	 *	@return		bool
	 */
	public function update( $data = array( ), $stripTags = TRUE )
	{
		if ( !( is_array( $data ) && $data ) )
			throw new InvalidArgumentException( 'Data for update must be an array and have atleast 1 pair');
		$this->validateFocus( );
		$has = $this->get( FALSE );
		if ( !$has )
			throw new InvalidArgumentException( 'No data sets focused for update');
		
		$conditions = $this->getConditionQuery( array() );
		$this->getTableLinks( $conditions );
			
		$updates = $this->setDataTocolumns( $data, $stripTags );
		if ( sizeof( $updates ) )
		{
			//saeid	{
			global $sw_log_edit;
			if ( $sw_log_edit )
			{
				global $log_edit;
				$dataP[ 'TableName' ] = $this->getTables( );
				$query = 'SELECT * FROM ' . $this->getTables( ) . ' WHERE ' . $conditions;
				$resultSet = $this->dbc->query( $query );
				if ( $resultSet )
					$dataP[ 'data' ] = $resultSet->fetchAll( $this->getFetchMode( ) );
				else
					$dataP[ 'data' ] = array( );
				$dataP[ 'columns' ] = $this->columns;
				$dataP[ 'mode' ] = 'update';
				array_push( $log_edit, $dataP );
			}
			//}			
			$updates = implode( ', ', $updates );
			$query = 'UPDATE ' . $this->getTables( FALSE ) . ' SET ' . $updates . ' WHERE ' . $conditions;
			$result = $this->dbc->exec( $query );
			return $result;
		}
	}

	/**
	 *	Updates data in table where conditions are given for.
	 *	@access		public
	 *	@param		array		$data			Array of data to store
	 *	@param		array		$conditions		Array of condition pairs
	 *	@param		bool		$stripTags		Flag: strip HTML tags from values
	 *	@return		bool
	 */

	public function updateByConditions( $data = array( ), $conditions = array( ), $stripTags = FALSE )
	{
		if ( !( is_array( $data ) && $data ) )
			throw new InvalidArgumentException( 'Data for update must be an array and have atleast 1 pair');
		if ( !( is_array( $conditions ) && $conditions ) )
			throw new InvalidArgumentException( 'Conditions for update must be an array and have atleast 1 pair');

		//		$updates = array( );
		$conditions = $this->getConditionQuery( $conditions, FALSE, FALSE );
		$this->getTableLinks( $conditions );

		$updates = $this->setDataTocolumns( $data, $stripTags );
		if ( sizeof( $updates ) )
		{
			//saeid	{
			global $sw_log_edit;
			if ( $sw_log_edit )
			{
				global $log_edit;
				$dataP[ 'TableName' ] = $this->getTables( );
				$query = 'SELECT * FROM ' . $this->getTables( ) . ' WHERE ' . $conditions;
				$resultSet = $this->dbc->query( $query );
				if ( $resultSet )
					$dataP[ 'data' ] = $resultSet->fetchAll( $this->getFetchMode( ) );
				else
					$dataP[ 'data' ] = array( );
				$dataP[ 'columns' ] = $this->columns;
				$dataP[ 'mode' ] = 'updateByConditions';
				array_push( $log_edit, $dataP );
			}
			//}	
			$updates = implode( ', ', $updates );
			$query = 'UPDATE ' . $this->getTables( ) . ' SET ' . $updates . ' WHERE ' . $conditions;
			$result = $this->dbc->exec( $query );
			return $result;
		}
	}

	/**
	 *	Removes all data and resets incremental counter.
	 *	Note: This method does not return the number of removed rows.
	 *	@access		public
	 *	@return		void
	 *	@see		http://dev.mysql.com/doc/refman/4.1/en/truncate.html
	 */

	public function truncate( )
	{
		$query = 'TRUNCATE ' . $this->getTableName( );
		$output =  $this->dbc->exec( $query );
		if($this->isJoin())
			foreach ($this->JoinList as $join){
				$outputJoin =  $join['ObjTableReader']->truncate();
				$output = $output & $outputJoin;
			}
		return  $output;
	}
	/**
	 * 
	 * Enter description here ...
	 * @param array $data
	 * @param bool $stripTags
	 */

	private function setDataTocolumns( $data, $stripTags )
	{
		$alias = $this->getAlias( );
		$updates = array( );
		foreach ( $this->columns as $column )
		{
			$columnB = str_replace( $alias . '.', '', $column );
			if ( isset( $data[ $column ] ) ||  isset( $data[ $columnB ] ) )
			{
				if( isset( $data[ $columnB ] )) $column = $columnB;
				if ( $stripTags )
					$data[ $column ] = strip_tags( $data[ $column ] );
				if ( $data[ $column ] == 'on' )
					$data[ $column ] = 1;
				$data[ $column ] = $this->secureValue( $data[ $column ] );
				$updates[ ] = '`' . $column . '`=' . $data[ $column ];
			}
		}
		if($this->isJoin()){
			foreach ($this->JoinList as $join){
				$updates = array_merge( $updates, $join['ObjTableReader']->setDataTocolumns( $data, $stripTags ) );
			}
		}	
		return $updates;
	}

	/**
	 *	@author		Saeid
	 *	
	 *	Convert the TableLink array to string  for delete and update.
	 *	@access		protected
	 *	@return		string
	 */

	protected function getTableLinks( &$conditions )
	{
		if ( $this->JoinList )
		{
			foreach ( $this->JoinList as $join )
			{
				if ( $conditions )
					$conditions .= ' and ';
				$ObjTableReader = $join[ 'ObjTableReader' ];
				$conditions .= $this->getTableLink( $ObjTableReader, $join[ 'TableLink' ] );

			}
		}
	}
}
?>

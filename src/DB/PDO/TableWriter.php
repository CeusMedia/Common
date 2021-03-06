<?php
/**
 *	Write Access for Database Tables.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_DB_PDO
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Write Access for Database Tables.
 *	@category		Library
 *	@package		CeusMedia_Common_DB_PDO
 *	@extends		DB_PDO_TableReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@deprecated		Please use CeusMedia/Database (https://packagist.org/packages/ceus-media/database) instead
 *	@todo			remove in version 1.0
 */
class DB_PDO_TableWriter extends DB_PDO_TableReader
{
	/**
	 *	Deletes focused Rows in this Table and returns number of affected Rows.
	 *	@access		public
	 *	@return		int
	 */
	public function delete(){
		$this->validateFocus();
		$conditions	= $this->getConditionQuery( array() );
		$query	= 'DELETE FROM '.$this->getTableName().' WHERE '.$conditions;
#		$has	= $this->get( FALSE );
#		if( !$has )
#			throw new \InvalidArgumentException( 'Focused Indices are not existing.' );
		return $this->dbc->exec( $query );
	}

	/**
	 *	Deletes data by given conditions.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@return		bool
	 */
	public function deleteByConditions( $where = array() ){
		//  render WHERE conditions, uncursored, without functions
		$conditions	= $this->getConditionQuery( $where, FALSE, FALSE, FALSE );
		$query	= 'DELETE FROM '.$this->getTableName().' WHERE '.$conditions;
		$result	= $this->dbc->exec( $query );
		$this->defocus();
		return $result;
	}

	/**
	 *	Inserts data into this table and returns ID.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from values
	 *	@return		int			ID of inserted row
	 */
	public function insert( $data = array(), $stripTags = TRUE ){
		$columns	= array();
		$values		= array();
		//  iterate Columns
		foreach( $this->columns as $column ){
			//  no Data given for Column
			if( !isset( $data[$column] ) )
				continue;
			$value = $data[$column];
			if( $stripTags )
				$value = strip_tags( $value );
			$columns[$column]	= $column;
			$values[$column]	= $this->secureValue( $value );
		}
		//  add focused indices to data
		if( $this->isFocused() ){
			//  iterate focused indices
			foreach( $this->focusedIndices as $index => $value ){
				//  Column is already set
				if( isset( $columns[$index] ) )
					continue;
				//  skip primary key
				if( $index == $this->primaryKey )
					continue;
				//  add key
				$columns[$index]	= $index;
				//  add value
				$values[$index]		= $this->secureValue( $value );
			}
		}
		//  get enumeration of masked column names
		$columns	= $this->getColumnEnumeration( $columns );
		$values		= implode( ', ', array_values( $values ) );
		$query		= 'INSERT INTO '.$this->getTableName().' ('.$columns.') VALUES ('.$values.')';
		$this->dbc->exec( $query );
		return $this->dbc->lastInsertId();
	}

	/**
	 *	Updating data of focused primary key in this table.
	 *	@access		public
	 *	@param		array		$data			Map of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML tags from values
	 *	@return		bool
	 */
	public function update( $data = array(), $stripTags = TRUE ){
		if( !( is_array( $data ) && $data ) )
			throw new \InvalidArgumentException( 'Data for update must be an array and have atleast 1 pair' );
		$this->validateFocus();
		$has	= $this->get( FALSE );
		if( !$has )
			throw new \InvalidArgumentException( 'No data sets focused for update' );
		$updates	= array();
		foreach( $this->columns as $column ){
			if( !array_key_exists($column, $data) )
				continue;
			$value	= $data[$column];
			if( $stripTags && $value !== NULL )
				$value	= strip_tags( $value );
			$value	= $this->secureValue( $value );
			$updates[] = '`'.$column.'`='.$value;
		}
		if( sizeof( $updates ) ){
			$updates	= implode( ', ', $updates );
			$query	= 'UPDATE '.$this->getTableName().' SET '.$updates.' WHERE '.$this->getConditionQuery( array() );
			$result	= $this->dbc->exec( $query );
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
	public function updateByConditions( $data = array(), $conditions = array(), $stripTags = FALSE ){
		if( !( is_array( $data ) && $data ) )
			throw new \InvalidArgumentException( 'Data for update must be an array and have atleast 1 pair' );
		if( !( is_array( $conditions ) && $conditions ) )
			throw new \InvalidArgumentException( 'Conditions for update must be an array and have atleast 1 pair' );

		$updates	= array();
		//  render WHERE conditions, uncursored, without functions
		$conditions	= $this->getConditionQuery( $conditions, FALSE, FALSE, FALSE );
		foreach( $this->columns as $column ){
			if( isset( $data[$column] ) ){
				if( $stripTags )
					$data[$column]	= strip_tags( $data[$column] );
				if( $data[$column] == 'on' )
					$data[$column] = 1;
				$data[$column]	= $this->secureValue( $data[$column] );
				$updates[] = '`'.$column.'`='.$data[$column];
			}
		}
		if( sizeof( $updates ) ){
			$updates	= implode( ', ', $updates );
			$query		= 'UPDATE '.$this->getTableName().' SET '.$updates.' WHERE '.$conditions;
			$result		= $this->dbc->exec( $query );
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
	public function truncate(){
		$query	= 'TRUNCATE '.$this->getTableName();
		return $this->dbc->exec( $query );
	}
}

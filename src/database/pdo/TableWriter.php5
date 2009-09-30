<?php
/**
 *	Write Access for Database Tables.
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
 *	@package		database.pdo
 *	@extends		Database_PDO_TableReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.database.pdo.TableReader' );
/**
 *	Write Access for Database Tables.
 *	@package		database.pdo
 *	@extends		Database_PDO_TableReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Database_PDO_TableWriter extends Database_PDO_TableReader
{
	/**
	 *	Deletes focused Rows in this Table and returns Number of Rows.
	 *	@access		public
	 *	@return		int
	 */
	public function delete()
	{
		$this->validateFocus();
		$conditions	= $this->getConditionQuery( array() );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
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
	public function deleteByConditions( $where = array() )
	{
		$conditions	= $this->getConditionQuery( $where );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
		$result	= $this->dbc->exec( $query );
		$this->defocus();
		return $result;
	}

	/**
	 *	Inserts data into this table and returns ID.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from values
	 *	@return		int
	 */
	public function insert( $data = array(), $stripTags = TRUE )
	{
		$keys	= array();
		$vals	= array();
		foreach( $this->columns as $column )										//  iterate Columns
		{
			if( !isset( $data[$column] ) )											//  no Data given for Column
				continue;
			$value = $data[$column];
			if( $stripTags )
				$value = strip_tags( $value );
			$keys[$column] = '`'.$column.'`';
			$vals[$column] = $this->secureValue( $value );
		}
		if( $this->isFocused() )													//  add focused Indices to Data
		{
			foreach( $this->focusedIndices as $key => $value )						//  iterate focused Indices
			{
				if( isset( $keys[$key] ) )											//  Column is already set
					continue;
				if( $key == $this->primaryKey )										//  skip primary Key
					continue;
				$keys[$key]	= '`'.$key.'`';													//  add Key
				$vals[$key]	= $this->secureValue( $value );							//  add Value
			}
		}
		$keys	= implode( ", ", array_values( $keys ) );
		$vals	= implode( ", ", array_values( $vals ) );
		$query	= "INSERT INTO ".$this->getTableName()." (".$keys.") VALUES (".$vals.")";
		$this->dbc->exec( $query );
		$id	= $this->dbc->lastInsertId();
		return $id;
	}

	/**
	 *	Updating data of focused primary key in this table.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from values
	 *	@return		bool
	 */
	public function update( $data = array(), $stripTags = TRUE )
	{
		if( !( is_array( $data ) && $data ) )
			throw new InvalidArgumentException( 'Data for Update must be an Array and have atleast 1 Pair.' );

		$this->validateFocus();
		$has	= $this->get( FALSE );
		if( !$has )
			throw new InvalidArgumentException( 'No Data Sets found for Update. Focused Indices are not existing.' );
		$updates	= array();
		foreach( $this->columns as $column )
		{
			if( !isset( $data[$column] ) )
				continue;
			$value	= $data[$column];
			if( $stripTags )
				$value	= strip_tags( $value );
			$value	= $this->secureValue( $value );
			$updates[] = '`'.$column.'`='.$value;
		}
		if( sizeof( $updates ) )
		{
			$updates	= implode( ", ", $updates );
			$query	= "UPDATE ".$this->getTableName()." SET $updates WHERE ".$this->getConditionQuery( array() );
			$result	= $this->dbc->exec( $query );
			return $result;
		}
	}

	/**
	 *	Updates data in table where conditions are given for.
	 *	@access		public
	 *	@param		array		$data			Array of Data to store
	 *	@param		array		$conditions		Array of Condition Pairs
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from Values
	 *	@return		bool
	 */
	public function updateByConditions( $data = array(), $conditions = array(), $stripTags = FALSE )
	{
		if( !( is_array( $data ) && $data ) )
			throw new InvalidArgumentException( 'Data for Update must be an Array and have atleast 1 Pair.' );
		if( !( is_array( $conditions ) && $conditions ) )
			throw new InvalidArgumentException( 'Conditions for Update must be an Array and have atleast 1 Pair.' );

		$conditions	= $this->getConditionQuery( $conditions, FALSE, FALSE );
		foreach( $this->columns as $column )
		{
			if( isset( $data[$column] ) )
			{
				if( $stripTags )
					$data[$column]	= strip_tags( $data[$column] );
				if( $data[$column] == "on" )
					$data[$column] = 1;
				$sets[]	= '`'.$column.'`'."='".$data[$column]."'";
			}
		}
		if( sizeof( $sets ) )
		{
			$ins_sets	= implode( ", ", $sets );
			$query	= "UPDATE ".$this->getTableName()." SET $ins_sets WHERE ".$conditions;
			$result	= $this->dbc->exec( $query );
			return $result;
		}
	}

	/**
	 *	Removes all Data and resets incremental counter.
	 *	Note: This method does not return the number of removed rows.
	 *	@access		public
	 *	@return		void
	 *	@see		http://dev.mysql.com/doc/refman/4.1/en/truncate.html
	 */
	public function truncate()
	{
		$query	= "TRUNCATE ".$this->getTableName();
		$this->dbc->exec( $query );
	}
}
?>
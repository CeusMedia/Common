<?php
import( 'de.ceus-media.database.pdo.TableReader' );
/**
 *	Write Access for Database Tables.
 *	@package		database.pdo
 *	@extends		Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Write Access for Database Tables.
 *	@package		database.pdo
 *	@extends		Database_PDO_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
			$keys[$column] = $column;
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
				$keys[$key]	= $key;													//  add Key
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
			$updates[] = $column."=".$value;
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
				$sets[]	= $column."='".$data[$column]."'";
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
	 *	Deletes all data in Table.
	 */
	public function truncate()
	{
		$query	= "TRUNCATE ".$this->getTableName();
		return $this->dbc->exec( $query );
	}
}
?>
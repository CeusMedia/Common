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
	 *	Deletes data of focused primary key in this table.
	 *	@access		public
	 *	@return		bool
	 */
	public function delete()
	{
		$this->check( 'focus' );
		$has	= $this->get( FALSE );
		if( !$has )
			throw new InvalidArgumentException( 'Focused Indices are not existing.' );
		$conditions	= $this->getConditionQuery( array() );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
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
#		$this->check( 'focus' );
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
	public function insert( $data = array(), $stripTags = FALSE )
	{
		$this->check( 'columns' );
		$keys	= array();
		$vals	= array();
		foreach( $this->columns as $column )
		{
//			if( $column == $this->primaryKey )
//				continue;
			if( !isset( $data[$column] ) )
				continue;
			$value = $data[$column];
			if( $stripTags )
				$value = strip_tags( $value );
			$keys[$column] = $column;
			$vals[$column] = $this->secureValue( $value );
		}
		if( $this->isFocused() == "index" )
		{
			foreach( $this->focusedIndices as $key => $value )
			{
				if( isset( $keys[$key] ) )
					continue;
				$keys[$key]	= $key;
				$vals[$key]	= $this->secureValue( $value );
			}
		}
		$keys	= implode( ", ", array_values( $keys ) );
		$vals	= implode( ", ", array_values( $vals ) );
		$query	= "INSERT INTO ".$this->getTableName()." (".$keys.") VALUES (".$vals.")";
		$this->dbc->exec( $query );
		$id	= $this->dbc->lastInsertId();
	//	$this->focusPrimary( $id );
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
		$this->check( 'columns' );
		$this->check( 'focus' );
		$has	= $this->get( FALSE );
		if( !$has )
		{
			remark( "Insufficient Keys on Select in Table ".$this->getTableName() );
			print_m( $this->focusedIndices );
			throw new InvalidArgumentException( 'Focused Indices are not existing. No Data Sets found for Update.' );
		}
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
	 *	@param		array		$data			associative Array of Data to store
	 *	@param		array		$where			associative Array of Condition Strings
	 *	@param		bool		$stripTags		Flag: strip HTML Tags from Values
	 *	@return		bool
	 */
	public function updateByConditions( $data = array(), $where = array(), $stripTags = FALSE )
	{
		$conditions	= $this->getConditionQuery( $where, $this->isFocused() == "primary" );
		foreach( $this->columns as $column )
		{
			if( $data[$column] )
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
			$result	= $this->dbc->query( $query );
			foreach( $this->columns as $column )
				$this->$column = $data[$column];
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
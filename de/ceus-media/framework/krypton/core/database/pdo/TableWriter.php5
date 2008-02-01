<?php
import( 'de.ceus-media.framework.krypton.core.database.pdo.TableReader' );
/**
 *	Write Access for Database Tables.
 *	@package		framework.krypton.core.database
 *	@extends		Framework_Krypton_Core_Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Write Access for Database Tables.
 *	@package		framework.krypton.core.database
 *	@extends		Framework_Krypton_Core_Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Framework_Krypton_Core_Database_PDO_TableWriter extends Framework_Krypton_Core_Database_PDO_TableReader
{
	/**
	 *	Deletes data of focused primary key in this table.
	 *	@access		public
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function delete()
	{
		$this->check( 'focus' );
		$conditions	= $this->getConditionQuery( array() );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
		return $this->dbc->exec( $query );
	}

	/**
	 *	Deletes data by given conditions.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
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
	 *	@param		bool		$strip_tags		strips HTML Tags from values
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		int
	 */
	public function insert( $data = array(), $strip_tags = false )
	{
		$this->check( 'columns' );
		$keys	= array();
		$vals	= array();
		foreach( $this->columns as $column )
		{
//			if( $column == $this->primary_key )
//				continue;
			if( !isset( $data[$column] ) )
				continue;
			$value = $data[$column];
			if( $strip_tags )
				$value = strip_tags( $value );
//			if( !ini_get( 'magic_quotes_gpc' ) )
				$value = addslashes( $value );
			$keys[$column] = $column;
			$vals[$column] = '"'.$value.'"';
		}
		if( $this->isFocused() && in_array( $this->focus_key, $this->getIndices() ) && !in_array( $this->focus_key, $keys ) )
		{
			$keys[$this->focus_key]	= $this->focus_key;
			$vals[$this->focus_key]	= $this->focus;
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
	 *	@param		array	$data		associative array of data to store
	 *	@param		bool	$strip_tags	strips HTML Tags from values
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function update( $data = array(), $strip_tags = true )
	{
		$this->check( 'columns' );
		$this->check( 'focus' );
		$has	= $this->get( false );
		if( sizeof( $has ) )
		{
			$updates	= array();
			foreach( $this->columns as $column )
			{
				if( !isset( $data[$column] ) )
					continue;
				$value	= $data[$column];
				if( $strip_tags )
					$value	= strip_tags( $value );
				if( !ini_get( 'magic_quotes_gpc' ) )
					$value	= addslashes( $value );
				$value	= str_replace( '"', "'", $value );
				$updates[] = $column.'="'.$value.'"';
			}
			if( sizeof( $updates ) )
			{
				$updates	= implode( ", ", $updates );
				$query	= "UPDATE ".$this->getTableName()." SET $updates WHERE ".$this->getConditionQuery( array() );
				$result	= $this->dbc->exec( $query );
				return $result;
			}
		}
		else
			return $this->insert( $data );
	}

	/**
	 *	Updates data in table where conditions are given for.
	 *	@access		public
	 *	@param		array	$data		associative Array of Data to store
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function updateByConditions( $data = array(), $where = array() )
	{
		$keys	= array();
		$vals	= array();
		$conditions	= $this->getConditionQuery( $where, $this->isFocused() == "primary" );
		foreach( $this->columns as $column )
		{
			if( $data[$column] )
			{
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
}
?>

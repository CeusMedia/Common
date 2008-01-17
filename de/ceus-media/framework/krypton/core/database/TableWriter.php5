<?php
import( 'de.ceus-media.framework.krypton.core.database.TableReader' );
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
class Framework_Krypton_Core_Database_TableWriter extends Framework_Krypton_Core_Database_TableReader
{
	/**
	 *	Deletes data of focused primary key in this table.
	 *	@access		public
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function delete( $debug = 1 )
	{
		$this->check( 'focus' );
		$conditions	= $this->getConditionQuery( array() );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
		return $this->dbc->exec( $query, $debug );
	}

	/**
	 *	Deletes data by given conditions.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function deleteByConditions( $where = array(), $debug = 1 )
	{
		$this->check( 'focus' );
		$conditions	= $this->getConditionQuery( $where );
		$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
		$result	= $this->dbc->exec( $query, $debug );
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
	public function insert( $data = array(), $strip_tags = false, $debug = 1 )
	{
		$this->check( 'fields' );
		$keys	= array();
		$vals	= array();
		foreach( $this->fields as $field )
		{
//			if( $field == $this->primary_key )
//				continue;
			if( !isset( $data[$field] ) )
				continue;
			$value = $data[$field];
			if( $strip_tags )
				$value = strip_tags( $value );
//			if( !ini_get( 'magic_quotes_gpc' ) )
				$value = addslashes( $value );
			$keys[$field] = $field;
			$vals[$field] = '"'.$value.'"';
		}
		if( $this->isFocused() && in_array( $this->focus_key, $this->getIndices() ) && !in_array( $this->focus_key, $keys ) )
		{
			$keys[$this->focus_key]	= $this->focus_key;
			$vals[$this->focus_key]	= $this->focus;
		}
		$keys	= implode( ", ", array_values( $keys ) );
		$vals	= implode( ", ", array_values( $vals ) );
		$query	= "INSERT INTO ".$this->getTableName()." (".$keys.") VALUES (".$vals.")";
		$this->dbc->query( $query, $debug );
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
	public function update( $data = array(), $strip_tags = true, $debug = 1 )
	{
		$this->check( 'fields' );
		$this->check( 'focus' );
		$has	= $this->get( false );
		if( sizeof( $has ) )
		{
			$updates	= array();
			foreach( $this->fields as $field )
			{
				if( !isset( $data[$field] ) )
					continue;
				$value	= $data[$field];
				if( $strip_tags )
					$value	= strip_tags( $value );
				if( !ini_get( 'magic_quotes_gpc' ) )
					$value	= addslashes( $value );
				$value	= str_replace( '"', "'", $value );
				$updates[] = $field.'="'.$value.'"';
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
	public function updateByConditions( $data = array(), $where = array(), $debug = 1 )
	{
		$keys	= array();
		$vals	= array();
		$conditions	= $this->getConditionQuery( $where, $this->isFocused() == "primary" );
		foreach( $this->fields as $field )
		{
			if( $data[$field] )
			{
				$data[$field]	= strip_tags( $data[$field] );
				if( $data[$field] == "on" )
					$data[$field] = 1;
				$sets[]	= $field."='".$data[$field]."'";
			}
		}
		if( sizeof( $sets ) )
		{
			$ins_sets	= implode( ", ", $sets );
			$query	= "UPDATE ".$this->getTableName()." SET $ins_sets WHERE ".$conditions;
			$result	= $this->dbc->query( $query, $debug );
			foreach( $this->fields as $field )
				$this->$field = $data[$field];
			return $result;
		}
	}
}
?>

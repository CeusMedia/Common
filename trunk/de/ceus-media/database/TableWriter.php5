<?php
import( 'de.ceus-media.database.TableReader' );
/**
 *	TableWriter.
 *	@package		database
 *	@extends		Database_TableReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	TableWriter.
 *	@package		database
 *	@extends		TableReader
 *	@author			Database_Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			finish Code Documentation (@param at methods)
 */
class Database_TableWriter extends Database_TableReader
{
	/**
	 *	Constructor.
	 *	@access		private
	 *	@param		Object		$dbc			Database Connection
	 *	@param		string		$tableName		table name
	 *	@param		array		$fields			all fields / columns of this table
	 *	@param		string		$primaryKey		name of the primary keys of this table
	 *	@param		int			$focus			focused primary key of this table
	 *	@return		void
	 */
	public function __construct( $dbc, $tableName, $fields = array(), $primaryKey = false, $focus = false )
	{
		parent::__construct( $dbc, $tableName, $fields, $primaryKey, $focus );
	}

	/**
	 *	Inserting data into this table.
	 *	@access		public
	 *	@param		array		$data			associative array of data to store
	 *	@param		bool		$stripTags		strips HTML Tags from values
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function addData( $data = array(), $stripTags = true, $debug = 1 )
	{
		if( sizeof( $this->fields ) )
		{
			$keys	= array();
			$vals	= array();
			foreach( $this->fields as $field )
			{
//				if( $field != $this->primaryKey )
//				{
					if( isset( $data[$field] ) )
						$value = $data[$field];
					else if( isset( $_POST["set_".$field] ) )
						$value = $_POST["set_".$field];
					else unset( $value );
					if( isset( $value ) )
					{
						if( $stripTags )
							$value = strip_tags( $value );
						$value = str_replace( '"', "'", $value );
						if ($value == "on")
							$value = 1;
						$keys[$field] = $field;
						if( !ini_get( 'magic_quotes_gpc' ) )
							$value = addslashes( $value );
						$vals[$field] = '"'.$value.'"';
					}
//				}
			}
			if( $this->isFocused() && in_array( $this->focusKey, $this->getForeignKeys() ) && !in_array( $this->focusKey, $keys ) )
			{
				$keys[$this->focusKey]	= $this->focusKey;
				$vals[$this->focusKey]	= $this->focus;
			}
			$keys	= implode( ", ", array_values( $keys ) );
			$vals	= implode( ", ", array_values( $vals ) );
			$query	= "INSERT INTO ".$this->getTableName()." (".$keys.") VALUES (".$vals.")";
			$this->dbc->Execute( $query, $debug );
			$id	= $this->dbc->getInsertId();
			$this->focusPrimary( $id );
			return $id;
		}
		return false;
	}

	/**
	 *	Deletes data of focused primary key in this table.
	 *	@access		public
	 *	@param		int			$debug			deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function deleteData( $debug = 1 )
	{
		if( $this->isFocused() )
		{
			$conditions	= $this->getConditionQuery( array() );
			$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
			return $this->dbc->Execute( $query, $debug );
		}
		return false;
	}

	/**
	 *	Deletes data of focused id in table.
	 *	@access		public
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function deleteDataWhere( $where = array(), $debug = 1 )
	{
		$conditions	= $this->getConditionQuery( $where );
		if( $conditions )
		{
			$query	= "DELETE FROM ".$this->getTableName()." WHERE ".$conditions;
			$result	= $this->dbc->Execute( $query, $debug );
			$this->defocus();
			return $result;
		}
	}

	/**
	 *	Inserting data into this table by calling table::addData().
	 *	@access		public
	 *	@param		array	$data		associative array of data to store
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		int
	 */
	public function insertData( $data = array(), $debug = 1 )
	{
		return $this->addData( $data, $debug );
	}

	/**
	 *	Updating data of focused primary key in this table.
	 *	@access		public
	 *	@param		array	$data		associative array of data to store
	 *	@param		bool	$stripTags	strips HTML Tags from values
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function modifyData( $data = array(), $stripTags = true, $debug = 1 )
	{
		if( $this->isFocused() )
		{
			if( sizeof( $this->fields ) )
			{
				$has	= $this->getData();
				if( sizeof( $has ) )
				{
					$updates	= array();
					foreach( $this->fields as $field )
					{
						if( isset( $data[$field] ) )
							$value	= $data[$field];
						else if( isset( $data["set_".$field] ) )
							$value	= $data["set_".$field];
						else if( isset( $_POST["set_".$field] ) )
							$value	= $_POST["set_".$field];
						else unset( $value );
					//	echo "field: $field -> $value<br>";
						if( isset( $value ) )
						{
							if( $stripTags )
								$value	= strip_tags( $value );
							if( !ini_get( 'magic_quotes_gpc' ) )
								$value	= addslashes( $value );
							$value	= str_replace( '"', "'", $value );
							if( $value == "on" )
								$value = 1;
							$updates[] = $field.'="'.$value.'"';
						}
					}
					if( sizeof( $updates ) )
					{
						$updates	= implode( ", ", $updates );
						$query	= "UPDATE ".$this->getTableName()." SET $updates WHERE ".$this->getConditionQuery( array() );
						$result	= $this->dbc->Execute( $query, $debug );
						return $result;
					}
				}
				else
					return $this->addData( $data );
			}
			else
				trigger_error( "Table '".$this->getTableName()."' is not well defined: Fields are missing.", E_USER_WARNING );
		}
		else
			trigger_error( "Table '".$this->getTableName()."' is not focused.", E_USER_WARNING );
	}

	/**
	 *	Modifies data of unfocused id in table where conditions are given.
	 *	@access		public
	 *	@param		array	$data		associative Array of Data to store
	 *	@param		array	$where		associative Array of Condition Strings
	 *	@param		int		$debug		deBug Level (16:die after, 8:die before, 4:remark, 2:echo, 1:count[default])
	 *	@return		bool
	 */
	public function modifyDataWhere( $data = array(), $where = array(), $debug = 1 )
	{
		$result		= FALSE;
		$conditions	= $this->getConditionQuery( $where, FALSE, FALSE );
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
		if( sizeof( $sets ) && $conditions )
		{
			$sets	= implode( ", ", $sets );
			$query	= "UPDATE ".$this->getTableName()." SET $sets WHERE ".$conditions;
			$result	= $this->dbc->Execute( $query, $debug );
		}
		return $result;
	}
}
?>
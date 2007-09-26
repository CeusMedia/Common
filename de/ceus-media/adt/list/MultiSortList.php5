<?php
import( 'de.ceus-media.adt.list.SortList' );
/**
 *	Sortable multidimensional array.
 *	@package		adt
 *	@subpackage		list
 *	@extends		sortableList
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.1
 */
/**
 *	Sortable multidimensional array.
 *	@package		adt
 *	@subpackage		list
 *	@extends		sortableList
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.1
 */
class MultiSortList extends SortList
{
	/**	@var	array		$_columns	Associative Array of column information */
	var $_columns = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array	$columns		Associative Array with column information
	 *	@param		array	$list			multidimensional associative Array of data
	 *	@return		void
	 */
	public function __construct( $columns = array(), $list = array() )
	{
		parent::__construct( $list );
		$this->setColumns( $columns );
	}

	/**
	 *	Add a column.
	 *	@access		public
	 *	@param		string	$key		Column key
	 *	@param		array	$name		Column name
	 *	@return		void
	 */
	function addColumn( $key, $name )
	{
		$this->_columns[$key] = $name;
	}

	/**
	 *	Adds an entry to the List.
	 *	@access		public
	 *	@param		array	$entry		Associative Array of data
	 *	@return		void
	 */
	function addEntry( $entry = array() )
	{
		foreach( $this->_columns as $key => $name )
			if( isset( $entry[$key] ) )
				$new_entry[$key] = $entry[$key];
		if( sizeof( $new_entry ) )
			$this->_list [] = $new_entry;
	}

	/**
	 *	Returns column key by a column name.
	 *	@access		public
	 *	@param		string	$name		Column name
	 *	@return		string
	 */
	function getColumnKey( $name )
	{
		foreach( $this->_columns as $key => $value )
			if( $value == $name )
				return $key;
	}

	/**
	 *	Returns column name of a column key.
	 *	@access		public
	 *	@param		string	$name		Column name
	 *	@return		string
	 */
	function getColumnName( $key )
	{
		return $this->_columns[$key];
	}

	/**
	 *	Returns all column definitions.
	 *	@access		public
	 *	@return		array
	 */
	function getColumns()
	{
		return $this->_columns;
	}

	/**
	 *	Removes a column by column name.
	 *	@access		public
	 *	@param		string	$name		Column name
	 *	@return		void
	 */
	function removeColumnByName( $name )
	{
		$key = $this->getColumnKey( $name );
		$this->removeColumn( $key );
	}

	/**
	 *	Removes a column by a column key.
	 *	@access		public
	 *	@param		string	$key		Column key
	 *	@return		void
	 */
	function removeColumn( $key )
	{
		foreach( $this->_list as $entry )
		{
			unset( $entry[$key] );
			$new_list [] = $entry;
		}
		$this->setList( $new_list );
	}

	/**
	 *	Sets column information.
	 *	@access		public
	 *	@param		array	$columns		Associative Array of column information
	 *	@return		void
	 */
	function setColumns( $columns = array() )
	{
		$this->_columns = $columns;
	}

	/**
	 *	Sorts List by column key and sort direction.
	 *	@access		public
	 *	@param		array	$key		Column Key
	 *	@param		string	$dir			Sort direction (asc|desc)
	 *	@return		void
	 */
	function sort( $key, $dir = "asc" )
	{
		$new_list = array();
		foreach( $this->_list as $entry )
		{
			$i = 0;
			for( $i=0; $i<sizeof( $new_list ); $i++ )
			{
				$line = $new_list[$i];
				if( strtolower( $line[$key] ) > strtolower( $entry[$key] ) )
					break;
			}
			$before = array_slice( $new_list, 0, $i );
			$after = array_slice( $new_list, $i );
			$new_list = array_merge( $before, array( $entry ), $after );
		}
		if( $dir == "desc" )
			$new_list = array_reverse( $new_list );
		$this->setList( $new_list );
	}

	/**
	 *	Sorts List by column name and sort direction.
	 *	@access		public
	 *	@param		string	$name		Column name to sort by
	 *	@param		string	$dir			Sort direction (asc|desc)
	 *	@return		void
	 */
	function sortByName( $name, $dir = "asc" )
	{
		$key = $this->getColumnKey( $name );
		$this->sort( $key, $dir );
	}
}
?>

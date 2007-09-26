<?php
/**
 *	Sortable List.
 *	@package		adt
 *	@subpackage		list
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.1
 */
/**
 *	Sortable List.
 *	@package		adt
 *	@subpackage		list
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@Ceus-Media.de>
 *	@version		0.1
 */
class SortList
{
	/**	@var		array	$_list			Associative Array of data */
	var $_list;
	/**	@var		int		$_pointer		Internal pointer in list */
	var $_pointer;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array	$list			Associative Array of data
	 *	@return		void
	 */
	function SortList( $list = array() )
	{
		$this->setList( $list );
		$this->resetList();
	}

	/**
	 *	Adds an entry to List.
	 *	@access		public
	 *	@param		string	$entry		Entry to add
	 *	@return		void
	 */
	function addEntry( $entry )
	{
		$this->_list[] = $entry;
	}

	/**
	 *	Returns List as array.
	 *	@access		public
	 *	@return		array
	 */
	function getList()
	{
		return $this->_list;
	}

	/**
	 *	Returns the size of the List.
	 *	@access		public
	 *	@return		int
	 */
	function getSize()
	{
		return sizeof( $this->_list );
	}

	/**
	 *	Returns next entry of List.
	 *	@access		public
	 *	@return		mixed
	 */
	function getNext()
	{
		if( $this->hasNext() )
		{
			$entry = $this->_list[$this->_pointer];
			$this->_pointer ++;
			return $entry;
		}
		else return false;
	}

	/**
	 *	Indicates whether a next entry is available in List.
	 *	@access		public
	 *	@return		bool
	 */
	function hasNext()
	{
		if( $this->_pointer < $this->getSize () )
			return true;
		return false;
	}

	/**
	 *	Resets internal pointer.
	 *	@access		public
	 *	@return		void
	 */
	function resetList()
	{
		$this->_pointer = 0;
	}

	/**
	 *	Sets List data.
	 *	@access		public
	 *	@param		array	$list			Associative Array of data
	 *	@return		void
	 */
	function setList( $list = array() )
	{
		$this->_list = $list;
	}

	/**
	 *	Sorts the List in a direction.
	 *	@access		public
	 *	@param		string	$dir			Sort direction (asc|desc)
	 *	@return		void
	 */
	function sort( $dir = "asc" )
	{
		if( $dir == "asc" )
			asort( $this->_list );
		else if( $dir == "desc" )
			arsort( $this->_list );
	}

	/**
	 *	Removes an entry from the List.
	 *	@access		public
	 *	@param		string	$entry		Value of entry
	 *	@return		void
	 */
	function removeEntry( $entry )
	{
		foreach( $this->_list as $line )
			if( $line != $entry )
				$new_list[] = $line;
		$this->setList( $new_list );
	}
}
?>
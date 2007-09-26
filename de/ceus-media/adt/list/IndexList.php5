<?php
import ("de.ceus-media.adt.list.ListElement");
/**
 *	List using Indexes for access.
 *	@package		adt.list
 *	@extends		Object
 *	@uses			Element
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.2
 */
/**
 *	List using Indexes for access.
 *	@package		adt.list
 *	@extends		Object
 *	@uses			Element
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.11.2004
 *	@version		0.2
 */
class IndexList
{
	/**	@var		array	$list		List of indexed Entries (Primitive Data Types or Objects) */
	protected $list = array();

	/**
	 *	Adds an Entry to the List.
	 *	@access		public
	 *	@param		mixed	$entry	Primitive data type  or Object
	 *	@return		void
	 */
	function addEntry( $entry )
	{
		$this->list[] = new ListElement( $entry );
	}

	/**
	 *	Adds an entire Array of Primitive Data Types or Objects to the List.
	 *	@access		public
	 *	@param		array	$list		non-associative Array of entries of all types (Primitives or Objects)
	 *	@return		void
	 */
	function addArray( $list )
	{
		foreach( $list as $entry )
		{
			$this->addEntry( $entry );
		}
	}
	
	/**
	 *	Returns an Entry by its Index.
	 *	@access		public
	 *	@param		index		$int	
	 *	@return		mixed
	 */
	function getEntry( $index )
	{
		if( $this->isEntry( $index ) )
		{
			return $this->list[$index];
		}
		return null;
	}

	/**
	 *	Returns the amount of Entries in this List.
	 *	@access		public
	 *	@return		int
	 */
	function getSize()
	{
		return count( $this->list );
	}

	/**
	 *	Indicates wheter an Entry with a specified Index exists.
	 *	@access		public
	 *	@param		index		$int	
	 *	@return		bool
	 */
	function isEntry( $index )
	{
		return isset( $this->list[$index] );
	}
}
?>
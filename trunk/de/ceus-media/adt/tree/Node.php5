<?php
/**
 *	Base Tree implementation.
 *	@package		adt.tree
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Base Tree implementation.
 *	@package		adt.tree
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class ADT_Tree_Node
{
	/**	@var	array		$children		Array of Children */
	protected $children = array ();
	
	/**
	 *	Adds a child to Tree.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@param		mixed		$child		Child to add
	 *	@return		void
	 */
	public function addChild( $name, $child )
	{
		$this->children[$name] = $child;
	}
	
	/**
	 *	Removes all children from Tree.
	 *	@access		public
	 *	@return		void
	 */
	public function clearChildren()
	{
		$this->children = array();
	}

	/**
	 *	Returns all children from Tree.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		array
	 */
	public function getChildren()
	{
		return array_values( $this->children() );
	}
	
	/**
	 *	Returns a child from Tree by its name.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		mixed
	 */
	public function getChild( $name )
	{
		return $this->children( $name );
	}
	
	/**
	 *	Indicates whether Tree has Children or not.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren()
	{
		return (bool) count( $this->children );
	}
	
	/**
	 *	Removes a Child from Tree by its name.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		bool
	 */
	public function removeChild( $name )
	{
		if( in_array( $name, $this->children ) )
		{
			unset( $this->children[$name] );
			return true;
		}
		return false;
	}
	
	/**
	 *	Returns Tree as representative array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->children();
	}
}
?>
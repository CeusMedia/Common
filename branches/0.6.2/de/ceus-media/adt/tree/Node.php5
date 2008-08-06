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
	protected $children		= array ();
	
	/**
	 *	Adds a child to Tree.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@param		mixed		$child		Child to add
	 *	@return		void
	 */
	public function addChild( $name, $child )
	{
		if( isset( $this->children[$name] ) )
			throw new InvalidArgumentException( 'A Child with Name "'.$name.'" is already existing.' );
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
		return $this->children;
	}
	
	/**
	 *	Returns a child from Tree by its name.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		mixed
	 */
	public function getChild( $name )
	{
		if( !array_key_exists( $name, $this->children ) )
			throw new InvalidArgumentException( 'A Child with Name "'.$name.'" is not existing.' );
		return $this->children[$name];
	}

	/**
	 *	Indicates whether Tree has Children or not.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		bool
	 */
	public function hasChild( $name )
	{
		return array_key_exists( $name, $this->children );
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
		if( !array_key_exists( $name, $this->children ) )
			return FALSE;
		unset( $this->children[$name] );
		return TRUE;
	}
}
?>
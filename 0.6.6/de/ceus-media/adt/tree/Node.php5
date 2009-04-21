<?php
/**
 *	Base Tree implementation.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		adt.tree
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Base Tree implementation.
 *	@package		adt.tree
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
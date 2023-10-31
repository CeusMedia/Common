<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Base Tree implementation.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Tree;

use InvalidArgumentException;

/**
 *	Base Tree implementation.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_Tree
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Node
{
	/**	@var	array		$children		Array of Children */
	protected $children		= [];

	/**
	 *	Adds a child to Tree.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@param		mixed		$child		Child to add
	 *	@return		void
	 *	@throws		InvalidArgumentException
	 */
	public function addChild( string $name, $child )
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
		$this->children = [];
	}

	/**
	 *	Returns a child from Tree by its name.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		mixed
	 */
	public function getChild( string $name )
	{
		if( !array_key_exists( $name, $this->children ) )
			throw new InvalidArgumentException( 'A Child with Name "'.$name.'" is not existing.' );
		return $this->children[$name];
	}

	/**
	 *	Returns all children from Tree.
	 *	@access		public
	 *	@return		array
	 */
	public function getChildren(): array
	{
		return $this->children;
	}

	/**
	 *	Indicates whether Tree has Children or not.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		bool
	 */
	public function hasChild( string $name ): bool
	{
		return array_key_exists( $name, $this->children );
	}

	/**
	 *	Indicates whether Tree has Children or not.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasChildren(): bool
	{
		return count( $this->children ) > 0;
	}

	/**
	 *	Removes a Child from Tree by its name.
	 *	@access		public
	 *	@param		string		$name		Child name
	 *	@return		bool
	 */
	public function removeChild( string $name ): bool
	{
		if( !array_key_exists( $name, $this->children ) )
			return FALSE;
		unset( $this->children[$name] );
		return TRUE;
	}
}

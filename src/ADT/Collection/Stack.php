<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Stack Implementation based on an Array. LIFO - last in first out.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Collection;

use Countable;
use RuntimeException;

/**
 *	Stack Implementation based on an Array. LIFO - last in first out.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Stack implements Countable
{
	public $delimiter	= '|';

	/**	@var		array		$stack			Array to hold Stack Items */
	protected $stack			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$initialArray	Array with initial Stack Items
	 *	@return		void
	 */
	public function __construct( array $initialArray = [] )
	{
		$this->stack = $initialArray;
	}

	/**
	 *	Returns the Stack as a String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString(): string
	{
		return "(".implode( $this->delimiter, $this->stack ).")";
	}

	/**
	 *	Returns bottom Item.
	 *	@access		public
	 *	@return		mixed
	 */
	public function bottom()
	{
		if( !count( $this->stack ) )
			throw new RuntimeException( 'Stack is empty.' );
		return array_shift( $this->stack );
	}

	/**
	 *	Returns number of Items in the Stack.
	 *	@access		public
	 *	@return		int
	 */
	public function count(): int
	{
		return count( $this->stack );
	}

	/**
	 *	Indicates whether an Item is in Stack or not.
	 *	@access		public
	 *	@param		mixed		$item		Item to find in the Stack
	 *	@return		bool
	 */
	public function has( $item ): bool
	{
		return in_array( $item, $this->stack, TRUE );
	}

	/**
	 *	Indicates whether the Stack is empty.
	 *	@access		public
	 *	@return		bool
	 */
	public function isEmpty(): bool
	{
		return ( 0 === $this->count() );
	}

	/**
	 *	Returns top Item of the Stack.
	 *	@access		public
	 *	@return		mixed
	 */
	public function pop()
	{
		if( $this->isEmpty() )
			throw new RuntimeException( 'Stack is already empty.' );
		return array_pop( $this->stack );
	}

	/**
	 *	Push a new Item onto the Stack.
	 *	@access		public
	 *	@param		mixed		$item		Item to add to the Stack
	 *	@return		int
	 */
	public function push( $item ): int
	{
		return array_push( $this->stack, $item );
	}

	/**
	 *	Returns the Stack as an Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray(): array
	{
		return $this->stack;
	}

	/**
	 *	Returns bottom Item.
	 *	@access		public
	 *	@return		mixed
	 */
	public function top()
	{
		if( !count( $this->stack ) )
			throw new RuntimeException( 'Stack is empty.' );
		return array_pop( $this->stack );
	}
}

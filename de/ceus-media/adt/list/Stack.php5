<?php
/**
 *	Stack Implementation based on an Array. LIFO - last in first out.
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
 *	@category		cmClasses
 *	@package		adt.list
 *	@implements		Countable
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Stack Implementation based on an Array. LIFO - last in first out.
 *	@category		cmClasses
 *	@package		adt.list
 *	@implements		Countable
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class ADT_List_Stack implements Countable
{
	/**	@var		array		$stack			Array to holf Stack Items */
	protected $stack = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$initialArray	Array with initial Stack Items
	 *	@return		void
	 */
	public function __construct( $initialArray = array() )
	{
		if( is_array( $initialArray ) && count( $initialArray ) )
			$this->stack = $initialArray;
	}

	/**
	 *	Returns the Stack as a String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString( $delimiter = "|" )
	{
		return "(".implode( $delimiter, $this->stack ).")";
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
	public function count()
	{
		return count( $this->stack );
	}

	/**
	 *	Indicates whether an Item is in Stack or not.
	 *	@access		public
	 *	@param		mixed		$item		Item to find in the Stack
	 *	@return		bood
	 */
	public function has( $item )
	{
		return in_array( $item, $this->stack );
	}

	/**
	 *	Indicates whether the Stack is empty.
	 *	@access		public
	 *	@return		bool
	 */
	public function isEmpty()
	{
		if( $this->count() == 0 )
			return true;
		return false;
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
		$value = array_pop( $this->stack );
		return $value;
	}

	/**
	 *	Push a new Item onto the Stack.
	 *	@access		public
	 *	@param		mixed		$item		Item to add to the Stack
	 *	@return		mixed
	 */
	public function push( $item )
	{
		return array_push( $this->stack, $item );
	}

	/**
	 *	Returns the Stack as an Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
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
?>
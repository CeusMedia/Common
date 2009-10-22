<?php
/**
 *	Queue Implementation based on an Array. FIFO - first in first out.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Queue Implementation based on an Array. FIFO - first in first out.
 *	@category		cmClasses
 *	@package		adt.list
 *	@implements		Countable
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class ADT_List_Queue implements Countable
{
	/**	@var		array		$queue			Array of all elements in queue */
 	protected $queue			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$initialArray	Array with initial Queue Items
	 *	@return		void
	 */
	public function __construct( $initialArray = false )
	{
		if( is_array( $initialArray ) && count( $initialArray ) )
			$this->queue = $initialArray;
	}

	/**
	 *	Returns all elements of this queue in a string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return "(".implode( "|", $this->queue ).")";
	}

	/**
	 *	Returns last Item of the Queue.
	 *	@access		public
	 *	@return		mixed
	 */
	public function bottom()
	{
		if( !count( $this->queue ) )
			throw new RuntimeException( 'Queue is empty.' );
		return array_pop( $this->queue );
	}

	/**
	 *	Returns the amount of elements in this queue.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->queue );
	}

	/**
	 *	Indicates whether an Item is in Queue or not.
	 *	@access		public
	 *	@param		mixed		$item		Item to find in the Queue
	 *	@return		bood
	 */
	public function has( $item )
	{
		return in_array( $item, $this->queue );
	}

	/**
	 *	Indicates whether the queue is empty.
	 *	@access		public
	 *	@return		bool
	 */
	public function isEmpty()
	{
		if( count( $this->queue ) == 0 )
			return TRUE;
		return FALSE;
	}

	/**
	 *	Returns next Item of the Queue.
	 *	@access		public
	 *	@return		mixed
	 */
	public function pop()
	{
		if( !count( $this->queue ) )
			throw new RuntimeException( 'Queue is empty.' );
		return array_shift( $this->queue );
	}

	/**
	 *	Adds a new Item to the Queue.
	 *	@access		public
	 *	@param		mixed		$item		Item to add to the Queue
	 *	@return		mixed
	 */
	public function push( $item )
	{
		$this->queue[] = $item;
		return $item;
	}

	/**
	 *	Returns all elements of this queue in an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->queue;
	}

	/**
	 *	Returns first element of this queue without removing it.
	 *	@access		public
	 *	@return		mixed
	 */
	public function top()
	{
		return $this->pop();
	}
}
?>
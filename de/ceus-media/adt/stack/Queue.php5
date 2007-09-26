<?php
/**
 *	Queue - FIFO.
 *	@package	adt
 *	@subpackage	queue
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Queue - FIFO.
 *	@package	adt
 *	@subpackage	queue
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Queue
{
	/**	@var	array	_queue			array of all elements in queue */
 	var $_queue = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array	queue		given queue as array
	 *	@return		void
	 */
	public function __construct( $queue = false )
	{
		if( is_array( $queue ) )
			$this->_queue = $queue;
		else if( $queue )
			$this->_queue[] = $queue;
	//	else $this->_queue = array ();
	}

	/**
	 *	Returns last element of this queue without removing it.
	 *	@access		public
	 *	@return		mixed
	 */
	function bottom()
	{
		if( $this->getSize() )
			return $this->_queue[$this->getSize()-1];
		return NULL;
	}

	/**
	 *	Returns first element of this queue and removes it from the queue.
	 *	@access		public
	 *	@return		mixed
	 */
	function dequeue()
	{
		$current = $this->top();
		$this->_queue = array_slice( $this->_queue, 1 );
		return $current;
	}

	/**
	 *	Adds an element to the queue.
	 *	@access		public
	 *	@param		mixed	value	value or element to enqueue
	 *	@return		void
	 */
	function enqueue( $value )
	{
		$this->_queue[] = $value;
	}

	/**
	 *	Indicates whether an Element is in Queue or not.
	 *	@access		public
	 *	@param		mixed	value	Element to find
	 *	@return		bood
	 */
	function has( $value )
	{
		return in_array( $value, $this->_queue );
	}

	/**
	 *	Returns the amount of elements in this queue.
	 *	@access		public
	 *	@return		int
	 */
	function getSize()
	{
		return sizeof( $this->_queue );
	}

	/**
	 *	Indicates whether the queue is empty.
	 *	@access		public
	 *	@return		bool
	 */
	function isEmpty()
	{
		if( $this->getSize() == 0 )
			return true;
	}

	/**
	 *	Returns first element of this queue and removes it from the queue.#
	 *	@access		public
	 *	@return		mixed
	 */
	function pop()
	{
		return $this->dequeue();
	}

	/**
	 *	Adds an element to the queue.
	 *	@access		public
	 *	@param		mixed	value	value or element to enqueue
	 *	@return		void
	 */
	function push( $value )
	{
		$this->enqueue( $value );
	}

	/**
	 *	Returns first element of this queue without removing it.
	 *	@access		public
	 *	@return		mixed
	 */
	function top()
	{
		return $this->_queue[0];
	}

	/**
	 *	Returns all elements of this queue in an array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		return $this->_queue;
	}

	/**
	 *	Returns all elements of this queue in a string.
	 *	@access		public
	 *	@return		string
	 */
	function toString()
	{
		return implode( "|", $this->_queue );
	}
}
?>
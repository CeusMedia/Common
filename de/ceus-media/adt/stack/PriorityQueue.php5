<?php
/**
 *	PriorityQueue - FIFO.
 *	@package	adt
 *	@subpackage	queue
 *	@extends	Queue
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	PriorityQueue - FIFO.
 *
 *	@package	adt
 *	@subpackage	queue
 *	@extends	Queue
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
import( 'de.ceus-media.adt.stack.Queue' );
class PriorityQueue extends Queue
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $queue = false )
	{
		parent::__construct( $queue );
	}

   	/**
	 *	Adds an element to the queue and sorts it by priority.
	 *	@access		public
	 *	@param		mixed	value	value or element to enqueue
	 *	@return		void
	 */
	function enqueue( $value )
	{
		$this->_queue[] = $value;
		if( $this->getSize() > 1 )
		{
			for( $i=$this->getSize()-1; $i>0; $i-- )
			{
				$current	= $this->_queue[$i];
				$upper		= $this->_queue[$i-1];
				if( $current > $upper )
				{
					$this->_queue[$i-1]	= $current;
					$this->_queue[$i]	= $upper;
				}
			}
		}
	}
}
?>
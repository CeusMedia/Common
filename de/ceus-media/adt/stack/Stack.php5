<?php
/**
 *	Stack - LIFO.
 *	@package		adt.stack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Stack - LIFO.
 *	@package		adt.stack
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Stack
{
	/**	@var	array	$stack		stack as array */
	protected $stack = array ();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$stack	given stack as array
	 *	@return		void
	 */
	public function __construct( $stack = array() )
	{
		if( is_array( $stack ) && count( $stack ) )
			$this->stack = $stack;
	}

	/**
	 *	Indicates whether the stack is empty.
	 *	@access		public
	 *	@return		bool
	 */
	public function isEmpty()
	{
		if( $this->getSize() == 0 )
			return true;
		return false;
	}

	/**
	 *	Returns the size of the stack.
	 *	@access		public
	 *	@return		int
	 */
	public function getSize()
	{
		return sizeof( $this->stack );
	}

	/**
	 *	Returns top element of the stack.
	 *	@access		public
	 *	@return		string
	 */
	public function pop()
	{
		if( !$this->isEmpty() )
		{
			$value = array_pop( $this->stack );
			return $value;
		}
		else return false;
	}

	/**
	 *	Push something onto the stack.
	 *	@access		public
	 *	@param		string		$value	value to be pushed
	 *	@return		int
	 */
	public function push( $value )
	{
		return array_push( $this->stack, $value );
	}

	/**
	 *	Returns the stack as an array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->stack;
	}

	/**
	 *	Returns the stack as a string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString( $delimiter = "|" )
	{
		return implode( $delimiter, $this->stack );
	}
}
?>
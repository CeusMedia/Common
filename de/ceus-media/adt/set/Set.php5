<?php
/**
 *	Set Implementation.
 *	@package		adt.set
 *	@implements		Countable
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Set Implementation.
 *	@package		adt.set
 *	@implements		Countable
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Set implements Countable
{
	/**	@var		array		$list		Array of Set Elements */
	protected $list 		= array();
	/**	@var		int			$pointer	Current position to read */
	protected $pointer	= -1;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		if( count( func_num_args ) != 0 )
		{
			$args = func_get_args();
			if( is_array( $args[0] ) )
				foreach( $args[0] as $element )
					$this->add( $element );
			else
			{
				foreach($args as $element )
					$this->add( $element );
			}
		}
		$this->rewind();
	}

	/**
	 *	Adds an Element to Set.
	 *	@access		public
	 *	@param		mixed		element		Element to add
	 *	@return		bool
	 */
	public function add( $element )
	{
		if( !$this->inSet( $element ) )
		{
			$this->list[] = $element;
			return true;
		}
		return false;
	}

	/**
	 *	Removes all Elements from Set.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		if( $this->getSize() )
		{
			$this->list = array();
			$this->rewind();
		}
	}

	/**
	 *	Returns amount of Elements in Set.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return sizeof( $this->list );
	}

	/**
	 *	Returns Element the internal pointer points at.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getCurrent()
	{
		if( $this->pointer >= 0 )
		{
			if( $this->list[$this->pointer] )
				return $this->list[$this->pointer];
		}
		else if( $this->hasNext() )
			return $this->getNext(); 
		return null;
	}
	
	/**
	 *	Returns previous Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getPrevoius()
	{
		if( $this->hasPrevious() )
		{
			$this->pointer--;
			return $this->getCurrent();
		}		
		return null;
	}
	
	/**
	 *	Returns next Element.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getNext()
	{
		if( $this->hasNext() )
		{
			$this->pointer++;
			return $this->getCurrent();
		}
		return null;
	}

	/**
	 *	Returns amount of Elements in Set.
	 *	@access		public
	 *	@return		int
	 */
	public function getSize()
	{
		return sizeof( $this->list );
	}

	/**
	 *	Indicates whether Set has an Element( alias for Set::inSet).
	 *	@access		public
	 *	@param		mixed		$element		Element to find.
	 *	@return		bool
	 */
	public function has( $element )
	{
		return $this->inSet( $element );
	}
	
	/**
	 *	Indicates whether Set hat more Elements.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasNext()
	{
		return $this->pointer + 1 < $this->getSize();
	}

	/**
	 *	Indicates whether Set has previous Elements.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasPrevious()
	{
		return $this->pointer > 0;
	}
	
	/**
	 *	Indicates whether Set has an Element.
	 *	@access		public
	 *	@param		mixed		element		Element to find
	 *	@return		bool
	 */
	public function inSet( $element )
	{
		$this->rewind();
	 	while( $this->hasNext() )
		{
	 		$current = $this->getNext();
			if( $element == $current )
				return true;
		}
		return false;
	}

	/**
	 *	Indicates whether Set is empty.
	 *	@access		public
	 *	@return		bool
	 */
	public function isEmpty()
	{
		return !$this->getSize();
	}

	/**
	 *	Sets internal pointer to start.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->pointer = -1;
	}

	/**
	 *	Returns all Elements as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array = array();
		$this->rewind();
		while( $this->hasNext() )
		{
		   	$element = $this->getNext();
			$array[] = $element;
		}
		return $array;
	}

	/**
	 *	Returns representative String of Set.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		$array = array();
		$this->rewind();
		while( $this->hasNext() )
		{
		   	$element = $this->getNext();
		   	if( is_object( $element ) )
				$array[] = $element->toString();
			else
				$array[] = $element;
		}
		$string = "{".implode( ", ", $array )."}";
		return $string;
	}
}
?>
<?php
/**
 *	JAVA like StringBuffer Implementation.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	JAVA like StringBuffer Implementation.
 *	@package		adt
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class ADT_StringBuffer implements Countable
{
	/**	@var		string		$buffer			internal String */
	private $buffer;
	/**	@var		string		$direction		internal Direction */
	private $direction = "";
	/**	@var		int			$pointer		internal Position Pointer */
	private $pointer = 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$buffer			initial String in StringBuffer
	 *	@return		void
	 */
	public function __construct( $buffer )
	{
		$this->buffer = $buffer;
	}

	/**
	 *	Returns the Size of the String.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return strlen( $this->buffer );
	}

	/**
	 *	Deletes a Character at a given Position.
	 *	@access		public
	 *	@param		int			$position			Position to delete
	 *	@return		string
	 */
	public function deleteCharAt( $position )
	{
		for( $i = 0; $i < $this->count(); $i++ )
			if( $position != $i )
				$chr_state = $chr_state.$this->buffer[$i];
		$this->buffer = $chr_state;
		if( $position == $this->pointer )
			$this->pointer++;
		return $this->toString();
	}

	/**
	 *	Returns the Character at a given Position.
	 *	@access		public
	 *	@param		int			$position			Position
	 *	@return		string
	 */
	public function getCharAt( $position )
	{
		if($position <= $this->count() && $position >= 0 )
			$character = $this->buffer[$position];
		return $character;
	}

	/**
	 *	Returns the current Position of the internal Pointer.
	 *	@access		public
	 *	@return		int
	 */
	public function getCurrentPos()
	{
		return $this->pointer;
	}

	/**
	 *	Returns  a Character at the current Position.
	 *	@access		public
	 *	@return		string
	 */
	public function getCurrentChar()
	{
		$character = $this->buffer[$this->pointer];
		return $character;
	}

	/**
	 *	Returns the next Character.
	 *	@access		public
	 *	@return		string
	 */
	public function getNextChar()
	{
		$character	= NULL;
		if( $this->direction == "<" )
			$this->pointer++;
		if( $this->pointer < $this->count() &&  $this->pointer >=0 )
		{
			$this->direction = ">";
			$character = $this->buffer[$this->pointer];
			$this->pointer++;
		}
		return $character;
	}

	/**
	 *	Returns the previous Character.
	 *	@access		public
	 *	@return		string
	 */
	public function getPrevChar()
	{
		if( $this->direction == ">" )
			$this->pointer--;
		if( $this->pointer <= $this->count() &&  $this->pointer > 0 )
		{
			$this->direction = "<";
			$this->pointer--;
			$character = $this->buffer[$this->pointer];
		}
		return $character;
	}

	/**
	 *	Indicates wheter less Characters are available.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasLess()
	{
		if( $this->pointer > 0 )
			return TRUE;
		return FALSE;
	}

	/**
	 *	Indicates wheter more Characters are available.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasMore()
	{
		if( $this->pointer < $this->count() )
			return TRUE;
		return FALSE;
	}

	/**
	 *	Inserts a String at a given Position.
	 *	@access		public
	 *	@param		int			$position		Position to insert to
	 *	@param		string		$string			String to insert
	 *	@return		string
	 */
	public function insert( $position, $string )
	{
		if( $position<= $this->count() && $position >=0 )
		{
			if( $position < $this->pointer )
				$this->pointer = $this->pointer + strlen( $string );
			$left	= substr( $this->toString(), 0, $position );
			$right	= substr( $this->toString(), $position );
			$this->buffer = $left.$string.$right;
		}
		return $this->toString();
	}

	/**
	 *	Resets Buffer, Pointer and Flags.
	 *	@access		public
	 *	@param		string		$buffer			new initial String in StringBuffer
	 *	@return		void
	 */
	public function reset( $buffer = "" )
	{
		$this->buffer	= $buffer;
		$this->resetPointer();
	}

	/**
	 *	Resets Pointer and Flags.
	 *	@access		public
	 *	@return		void
	 */
	public function resetPointer()
	{
		$this->pointer		= 0;
		$this->direction	= FALSE;
	}

	/**
	 *	Sets the Character at a given Position.
	 *	@access		public
	 *	@param		int			$position		Position to set to
	 *	@param		string		$characte		Character to set
	 *	@return		string
	 */
	public function setCharAt( $position, $character )
	{
		if( $position <= $this->count() && $position >= 0 )
			$this->buffer[$position] = $character;
		return $this->toString();
	}

	/**
	 *	Returns the current String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		return $this->buffer;
	}
}
?>
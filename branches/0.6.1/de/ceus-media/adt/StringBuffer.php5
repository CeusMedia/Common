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
class StringBuffer
{
	/**	@var	string	$buffer		internal String */
	private $buffer;
	/**	@var	string	$direction	internal Direction */
	private $direction = "";
	/**	@var	int		$pointer		internal Position Pointer */
	private $pointer = 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$buffer		initial String in StringBuffer
	 *	@return		void
	 */
	public function __construct( $buffer )
	{
		$this->buffer = $buffer;
	}

	/**
	 *	Deletes a Character at a given Position.
	 *	@access		public
	 *	@param		int			$pos		Position to delete
	 *	@return		string
	 */
	public function deleteCharAt( $pos )
	{
		for( $i = 0; $i < $this->getSize(); $i++ )
			if( $pos != $i )
				$chr_state = $chr_state.$this->buffer[$i];
		$this->buffer = $chr_state;
		if( $pos == $this->pointer )
			$this->pointer++;
		return $this->toString();
	}

	/**
	 *	Returns the Character at a given Position.
	 *	@access		public
	 *	@param		int			$pos		Position
	 *	@return		string
	 */
	public function getCharAt( $pos )
	{
		if($pos <= $this->getSize() && $pos >= 0 )
			$chr = $this->buffer[$pos];
		return $chr;
	}

	/**
	 *	Returns the current Position of the internal Pointer.
	 *	@access		public
	 *	@return		int
	 */
	public function getCurrentPos()
	{
		if( $this->direction == ">" )
			return $this->pointer-1;
		return $this->pointer;
	}

	/**
	 *	Returns  a Character at the current Position.
	 *	@access		public
	 *	@return		string
	 */
	public function getCurrentChar()
	{
		$chr = $this->buffer[$this->pointer];
		return $chr;
	}

	/**
	 *	Returns the next Character.
	 *	@access		public
	 *	@return		string
	 */
	public function getNextChar()
	{
		if( $this->direction == "<" )
			$this->pointer++;
		if( $this->pointer < $this->getSize() &&  $this->pointer >=0 )
		{
			$this->direction = ">";
			$chr = $this->buffer[$this->pointer];
			$this->pointer++;
		}
		return $chr;
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
		if( $this->pointer <= $this->getSize() &&  $this->pointer > 0 )
		{
			$this->direction = "<";
			$this->pointer--;
			$chr = $this->buffer[$this->pointer];
		}
		return $chr;
	}

	/**
	 *	Returns the Size of the String.
	 *	@access		public
	 *	@return		int
	 */
	public function getSize()
	{
		return strlen( $this->buffer );
	}

	/**
	 *	Indicates wheter less Characters are available.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasLess()
	{
		if( $this->pointer > 0 )
			return true;
		return false;
	}

	/**
	 *	Indicates wheter more Characters are available.
	 *	@access		public
	 *	@return		bool
	 */
	public function hasMore()
	{
		if( $this->pointer < $this->getSize() )
			return true;
		return false;
	}

	/**
	 *	Inserts a String at a given Position.
	 *	@access		public
	 *	@param		int			$pos			Position to insert to
	 *	@param		string		$string			String to insert
	 *	@return		string
	 */
	public function insert( $pos, $string )
	{
		if( $pos<= $this->getSize() && $pos >=0 )
		{
			if( $pos < $this->pointer )
				$this->pointer = $this->pointer + strlen( $string );
			$left		= substr( $this->toString(), 0, $pos );
			$right	= substr( $this->toString(), $pos, $this->getSize() );
			$this->buffer = $left.$string.$right;
		}
		return $this->toString();
	}

	/**
	 *	Resets Buffer, Pointer and Flags.
	 *	@access		public
	 *	@param		string		$buffer		new initial String in StringBuffer
	 *	@return		void
	 */
	public function reset( $buffer = "" )
	{
		$this->buffer	= $buffer;
		$this->resetPointer();
	}

	/**
	 *	Sets the Character at a given Position.
	 *	@access		public
	 *	@param		int			$pos			Position to set to
	 *	@param		string		$chr			Character to set
	 *	@return		string
	 */
	public function setCharAt( $pos, $chr )
	{
		if($pos <= $this->getSize() && $pos >= 0 )
			$this->buffer[$pos] = $chr;
		return $this->toString();
	}

	/**
	 *	Returns the current String.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		if( $this->getSize() > 0 )
			return $this->buffer;
		return "";
	}

	/**
	 *	Resets Pointer and Flags.
	 *	@access		public
	 *	@return		void
	 */
	public function resetPointer()
	{
		$this->pointer = 0;
		$this->direction = false;
	}
}
?>
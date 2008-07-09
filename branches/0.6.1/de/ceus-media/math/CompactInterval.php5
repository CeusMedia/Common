<?php
/**
 *	Compact Interval (closed on both sides).
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Compact Interval (closed on both sides).
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_CompactInterval
{
	/**	@var		mixed		$end		End of Interval */
	protected $end;
	/**	@var		string		$name		Name of Interval (default I) */
	protected $name				= "I";
	/**	@var		mixed		$start		Start of Interval */
	protected $start;	
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@param		mixed		$end		End of Interval
	 */
	public function __construct( $start, $end, $name = NULL )
	{
		$this->setStart( $start );
		$this->setEnd( $end );
		if( $name )
			$this->name	= $name;
	}
	
	/**
	 *	Returns distance between Start and End.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@param		mixed		$end		End of Interval
	 */
	public function getDiameter()
	{
		return abs( $this->end - $this->start );	
	}

	/**
	 *	Returns Start of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getStart()
	{
		return $this->start;
	}
	
	/**
	 *	Returns End of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	public function getEnd()
	{
		return $this->end;
	}
	
	/**
	 *	Sets Start of Interval.
	 *	@access		public
	 *	@param		mixed		$start		Start of Interval
	 *	@return		void
	 */
	public function setStart( $start )
	{
		if( $this->end && $start > $this->end )
			throw new InvalidArgumentException( 'Start of Interval cannot be greater than End.' );
		$this->start	= $start;	
	}
	
	/**
	 *	Sets End of Interval.
	 *	@access		public
	 *	@param		mixed		$end		End of Interval
	 *	@return		void
	 */
	public function setEnd( $end )
	{
		if( $this->start && $end < $this->start )
			throw new InvalidArgumentException( 'End of Interval cannot be lower than Start.' );
		$this->end	= $end;	
	}

	/**
	 *	Returns Interval as mathematical String.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		return $this->name."[".$this->start.";".$this->end."]";
	}
}
?>
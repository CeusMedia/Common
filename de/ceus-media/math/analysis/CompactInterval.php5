<?php
/**
 *	Compact Interval (closed on both sides).
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Compact Interval (closed on both sides).
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class CompactInterval
{
	/**	@var	mixed	$_start	Start of Interval */
	var $_start;
	/**	@var	mixed	$_end	End of Interval */
	var $_end;
	
	/**

	 *	Constructor.
	 *	@access		public
	 *	@param		mixed	$start		Start of Interval
	 *	@param		mixed	$end		End of Interval
	 */
	public function __construct ($start, $end)
	{
		$this->_setStart ($start);
		$this->_setEnd ($end);
	}
	
	/**
	 *	Returns distance between Start and End.
	 *	@access		public
	 *	@param		mixed	$start		Start of Interval
	 *	@param		mixed	$end		End of Interval
	 */
	function getDiam ()
	{
		return abs($this->getEnd() - $this->getStart());	
	}

	/**
	 *	Returns Start of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	function getStart ()
	{
		return $this->_start;
	}
	
	/**
	 *	Returns End of Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	function getEnd ()
	{
		return $this->_end;
	}
	
	/**
	 *	Sets Start of Interval.
	 *	@access		public
	 *	@param		mixed	$start		Start of Interval
	 *	@return		void
	 */
	function _setStart ($start)
	{
		$this->_start	= $start;	
	}
	
	/**
	 *	Sets End of Interval.
	 *	@access		public
	 *	@param		mixed	$end		End of Interval
	 *	@return		void
	 */
	function _setEnd ($end)
	{
		if ($end < $this->getStart ())
			trigger_error( "End of Interval cannot be lower than Start", E_USER_ERROR );
		$this->_end	= $end;	
	}

	/**
	 *	Returns Interval as mathematical String.
	 *	@access		public
	 *	@param		string	$name		Name of Interval
	 *	@return		string
	 */
	function toString ($name = "I")
	{
		$string = $name."[".$this->getStart().";".$this->getEnd()."]";
		return $string;
	}
}
?>
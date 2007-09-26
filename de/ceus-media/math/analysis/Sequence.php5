<?php
/**
 *	Sequence within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Sequence within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Sequence
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$formula		Formula of Sequence
	 *	@param		Interval		$interval		Interval of Sequence
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		$this->_formula	= $formula;
		$this->_interval	= $interval;
	}

	/**
	 *	Returns Formula Expression.
	 *	@access		public
	 *	@return		string
	 */
	function getExpression()
	{
		return $this->_formula->getExpression();
	}
	
	/**
	 *	Calculates Value of Index within Sequence.
	 *	@access		public
	 *	@param		int			$index		Index of Value within Sequence
	 *	@return		double
	 */
	function getValue( $index )
	{
		return $this->_formula->getValue( $index );
	}

	/**
	 *	Indicates whether this Sequence is convergent.
	 *	@access		public
	 *	@return		bool
	 */
	function isConvergent ()
	{
		for ($i=$this->_interval->getStart(); $i<$this->_interval->getEnd(); $i++)
		{
			$diff = abs ($this->getValue ($i+1) - $this->getValue ($i));		
			if (!$old_diff) $old_diff = $diff;
			else
			{
				if ($diff >= $old_diff) 
					return false;
			}
		}
		return true;
	}

	/**
	 *	Indicates whether this Sequence is divergent.
	 *	@access		public
	 *	@return		bool
	 */
	function isDivergent ()
	{
		return !$this->isConvergent ();
	}

	/**
	 *	Returns Sequence as Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray ()
	{
		$array = array ();
		for ($i=$this->_interval->getStart(); $i<$this->_interval->getEnd(); $i++)
		{
			$value = $this->getValue ($i);
			$array [$i] = $value;
		}
		return $array;	
	}
	
	/**
	 *	Returns Sequence as HTML Table.
	 *	@access		public
	 *	@return		array
	 */
	function toTable ()
	{
		$array = $this->toArray ();
		$code = "<table cellpadding=2 cellspacing=0 border=1>";
		foreach ($array as $key => $value) $code .= "<tr><td>".$key."</td><td>".round($value,8)."</td></tr>";
		$code .= "</table>";
		return $code;
	}
}
?>
<?php
import  ("de.ceus-media.math.analysis.Sequence");
/**
 *	Progression within a compact Interval.
 *	@package	math
 *	@subpackage	analysis
 *	@extends	Sequence
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.05.2006
 *	@version		0.1
 */
/**
 *	Progression within a compact Interval.
 *	@package	math
 *	@subpackage	analysis
 *	@extends	Sequence
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		09.05.2006
 *	@version		0.1
 *	@todo		Code Correction
 */
class Progression extends Sequence
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$formula		Formula of Progression
	 *	@param		Interval		$interval		Interval of Progression
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		parent::__construct( $formula, $interval );
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
	 *	Calculates partial Sum of Progression.
	 *	@access		public
	 *	@param		int			$from		Interval Start
	 *	@param		int			$to			Interval End
	 *	@return		double
	 */
	function getPartialSum( $from, $to )
	{
		for( $i=$from; $i<=$to; $i++ )
			$sum += $this->getValue( $i );
		return $sum;
	}

	/**
	 *	Calculates partial Sum of Progression within given Interval.
	 *	@access		public
	 *	@return		void
	 */
	function getSum()
	{
		return $this->getPartialSum( $this->_interval->getStart(), $this->_interval->getEnd() );
	}

	/**
	 *	Indicates whether this Progression is convergent.
	 *	@access		public
	 *	@return		bool
	 *	@todo		correct Function: harmonic progression is convergent which is WRONG
	 */
	function isConvergent ()
	{
		$is = true;
		for( $i=$this->_interval->getStart(); $i<$this->_interval->getEnd(); $i++ )
		{
			$an = $this->getPartialSum( $this->_interval->getStart(), $i );
			$an1 = $this->getPartialSum( $this->_interval->getStart(), $i+1 );
			$diff = abs( $an1 - $an );
//			echo "<br>an1: ".$an1." | an: ".$an." | diff: ".$diff; 
			if (!$old_diff) $old_diff = $diff;
			else if( $diff >= $old_diff )
				$is = false;
		}
		return $is;
	}

	/**
	 *	Indicates whether this Progression is divergent.
	 *	@access		public
	 *	@return		bool
	 *	@todo		correct Function: harmonic progression is convergent which is WRONG
	 */
	function isDivergent()
	{
		return !$this->isConvergent();
	}

	/**
	 *	Returns Sequence of Partial Sums as Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		$array = array();
		for( $i=$this->_interval->getStart(); $i<$this->_interval->getEnd(); $i++ )
		{
			$value = $this->getPartialSum( $this->_interval->getStart(), $i );
			$array[$i] = $value;
		}
		return $array;	
	}
	
	/**
	 *	Returns Sequence of Partial Sums as HTML Table.
	 *	@access		public
	 *	@return		array
	 */
	function toTable()
	{
		$array = $this->toArray();
		$code = "<table cellpadding=2 cellspacing=0 border=1>";
		foreach( $array as $key => $value )
			$code .= "<tr><td>".$key."</td><td>".round( $value,8 )."</td></tr>";
		$code .= "</table>";
		return $code;
	}
}
?>
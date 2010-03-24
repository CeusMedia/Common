<?php
/**
 *	Progression within a compact Interval.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		math.analysis
 *	@extends		Math_Analysis_Sequence
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.05.2006
 *	@version		$Id$
 */
import( 'de.ceus-media.math.analysis.Sequence' );
/**
 *	Progression within a compact Interval.
 *	@category		cmClasses
 *	@package		math.analysis
 *	@extends		Math_Analysis_Sequence
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			09.05.2006
 *	@version		$Id$
 *	@todo			Code Correction
 */
class Math_Analysis_Progression extends Math_Analysis_Sequence
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula of Progression
	 *	@param		Math_CompactInterval	$interval		Interval of Progression
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
	public function getExpression()
	{
		return $this->formula->getExpression();
	}
	
	/**
	 *	Calculates partial Sum of Progression.
	 *	@access		public
	 *	@param		int			$from		Interval Start
	 *	@param		int			$to			Interval End
	 *	@return		double
	 */
	public function getPartialSum( $from, $to )
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
	public function getSum()
	{
		return $this->getPartialSum( $this->interval->getStart(), $this->interval->getEnd() );
	}

	/**
	 *	Indicates whether this Progression is convergent.
	 *	@access		public
	 *	@return		bool
	 *	@todo		correct Function: harmonic progression is convergent which is WRONG
	 */
	public function isConvergent ()
	{
		$is = true;
		for( $i=$this->interval->getStart(); $i<$this->interval->getEnd(); $i++ )
		{
			$an = $this->getPartialSum( $this->interval->getStart(), $i );
			$an1 = $this->getPartialSum( $this->interval->getStart(), $i+1 );
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
	public function isDivergent()
	{
		return !$this->isConvergent();
	}

	/**
	 *	Returns Sequence of Partial Sums as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		$array = array();
		for( $i=$this->interval->getStart(); $i<$this->interval->getEnd(); $i++ )
		{
			$value = $this->getPartialSum( $this->interval->getStart(), $i );
			$array[$i] = $value;
		}
		return $array;	
	}
	
	/**
	 *	Returns Sequence of Partial Sums as HTML Table.
	 *	@access		public
	 *	@return		array
	 */
	public function toTable()
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
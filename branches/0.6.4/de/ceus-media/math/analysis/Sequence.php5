<?php
/**
 *	Sequence within a compact Interval.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		math.analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Sequence within a compact Interval.
 *	@package		math.analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Math_Analysis_Sequence
{
	/**	@var		Math_Formula			$formula		Formula to integrate */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval to integrate within */
	protected $interval;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula of Sequence
	 *	@param		Math_CompactInterval	$interval		Interval of Sequence
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		$this->formula	= $formula;
		$this->interval	= $interval;
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
	 *	Calculates Value of Index within Sequence.
	 *	@access		public
	 *	@param		int			$index		Index of Value within Sequence
	 *	@return		double
	 */
	public function getValue( $index )
	{
		return $this->formula->getValue( $index );
	}

	/**
	 *	Indicates whether this Sequence is convergent.
	 *	@access		public
	 *	@return		bool
	 */
	public function isConvergent ()
	{
		for ($i=$this->interval->getStart(); $i<$this->interval->getEnd(); $i++)
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
	public function isDivergent ()
	{
		return !$this->isConvergent ();
	}

	/**
	 *	Returns Sequence as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray ()
	{
		$array = array ();
		for ($i=$this->interval->getStart(); $i<$this->interval->getEnd(); $i++)
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
	public function toTable ()
	{
		$array = $this->toArray ();
		$code = "<table cellpadding=2 cellspacing=0 border=1>";
		foreach ($array as $key => $value) $code .= "<tr><td>".$key."</td><td>".round($value,8)."</td></tr>";
		$code .= "</table>";
		return $code;
	}
}
?>
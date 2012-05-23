<?php
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Math.Analysis.Interpolation
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *	@category		cmClasses
 *	@package		Math.Analysis.Interpolation
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
class Math_Analysis_Interpolation_RegulaFalsi
{
	/**	@var		array					$data		Array of x and y values (Xi->Fi) */
	protected $data							= array();
	/**	@var		Math_Formula			$formula	Formula */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval	Interval */
	protected $interval;

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$toleranz	Tolerates Distance within Algorithm
	 *	@return		double
	 */
	public function interpolate( $tolerance )
	{
		$a	= $this->interval->getStart();
		$b	= $this->interval->getEnd();
		$c	= false;
		do{
			$ya	= $this->formula->getValue( $a );
			$yb	= $this->formula->getValue( $b );

			if( $ya * $yb > 0 )
			{
				throw new InvalidArgumentException( 'Formula has no 0 in Interval '.(string)$this->interval.'.' );
				break;
			}
			$c	= ( $a * $yb - $b * $ya ) / ( $yb - $ya );
			$found = $c - $a <= $tolerance || $b - $c <= $tolerance;
			if( $c >= 0 && $a >= 0 || $c < 0 && $a < 0 )
				$a	= $c;
			else 
				$b	= $c;
		}
		while( !$found );
		return $c;
	}

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		string		$formula	Formula Expression
	 *	@param		array		$variables	Variables in Formula
	 *	@return		void
	 */
	public function setFormula( $formula, $variables )
	{
		$this->formula	= new Math_Formula( $formula, $variables );
	}

	/**
	 *	Sets Interval data to start at.
	 *	@access		public
	 *	@param		int			$start		Start of Interval
	 *	@param		int			$end		End of Interval
	 *	@return		void
	 */
	public function setInterval( $start, $end )
	{
		if( $start * $end > 0 )
			throw new InvalidArgumentException( 'Interval needs to start below 0.' );
		$this->interval	= new Math_CompactInterval( $start, $end );
	}
}
?>
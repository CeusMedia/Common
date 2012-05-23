<?php
/**
 *	Bisection Interpolation within a compact Interval.
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
 *	@package		Math.Analysis
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
/**
 *	Bisection Interpolation within a compact Interval.
 *	@category		cmClasses
 *	@package		Math.Analysis
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
class Math_Analysis_Bisection
{
	/**	@var		Math_Formula	$formula		Formula Object */
	protected $formula				= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array			$formula		Formula Expression
	 *	@param		array			$formula		Formula Variables
	 *	@return		void
	 */
	public function setFormula( $formula, $vars )
	{
		$this->formula	= new Math_Formula( $formula, array( $vars ) );
	}

	/**
	 *	Sets Interval data to start at.
	 *	@access		public
	 *	@param		int			$start				Start of Interval
	 *	@param		int			$end				End of Interval
	 *	@return		void
	 */
	public function setInterval( $start, $end )
	{
		$this->interval	= new Math_CompactInterval( $start, $end );
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		tolerance		Tolerated Difference
	 *	@return		double
	 */
	public function interpolate( $tolerance )
	{
		$a	= $this->interval->getStart();
		$b	= $this->interval->getEnd();
		$c	= false;
		while( true )
		{
			$ya	= $this->formula->getValue( $a );
			$yb	= $this->formula->getValue( $b );

			if( $ya * $yb > 0 )
				throw new RuntimeException( 'Formula has no null in Interval['.$a.','.$b.'].' );
			
			$c	= ( $a + $b ) / 2;
			
			if( $b - $a <= $tolerance )
				return $c;
			$yc	= $this->formula->getValue( $c );

			if( $ya * $yc <=0 )
				$b	= $c;
			else
				$a	= $c;
		}
		return $c;
	}
}
?>
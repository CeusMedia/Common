<?php
/**
 *	Bisection Interpolation within a compact Interval.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math_Analysis
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			03.02.2006
 */
/**
 *	Bisection Interpolation within a compact Interval.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math_Analysis
 *	@uses			Alg_Math_Formula
 *	@uses			Alg_Math_CompactInterval
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			03.02.2006
 */
class Alg_Math_Analysis_Bisection
{
	/**	@var		Alg_Math_Formula	$formula		Formula Object */
	protected $formula				= array();

	public function __construct(){
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
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

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array			$formula		Formula Expression
	 *	@param		array			$formula		Formula Variables
	 *	@return		void
	 */
	public function setFormula( $formula, $vars )
	{
		$this->formula	= new Alg_Math_Formula( $formula, array( $vars ) );
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
		$this->interval	= new Alg_Math_CompactInterval( $start, $end );
	}
}

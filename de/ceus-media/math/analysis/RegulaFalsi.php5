<?php
import( 'de.ceus-media.math.Formula' );
import( 'de.ceus-media.math.analysis.CompactInterval' );
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@uses			CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@uses			CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class RegulaFalsi
{
	/**	@var	array		$_data			Array of x and y values (Xi->Fi) */
	var $_data		= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data		Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	function setFormula( $formula, $vars )
	{
		$this->_formula	= new Formula( $formula, array( $vars ) );
	}

	/**
	 *	Sets Interval data to start at.
	 *	@access		public
	 *	@param		array		$data		Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	function setInterval( $start, $end )
	{
		$this->_interval	= new CompactInterval( $start, $end );
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@return		double
	 */
	function interpolate( $tolerance )
	{
		$a	= $this->_interval->getStart();
		$b	= $this->_interval->getEnd();
		$c	= false;
		do{
			$ya	= $this->_formula->getValue( $a );
			$yb	= $this->_formula->getValue( $b );

			if( $ya * $yb > 0 )
			{
				trigger_error( "Formula has no null in Interval[".$a.",".$b."]", E_USER_WARNING );
				break;
			}
			$c	= ( $a * $yb - $b * $ya ) / ( $yb - $ya );
			$found = $c - $a <= $tolerance || $b - $c <= $tolerance;
			if( $c >= 0 && $a >= 0 || $c < 0 && $a < 0 )
				$a	= $c;
			else 
				$b	= $c;
			$yc	= $this->_formula->getValue( $c );
		}
		while( !$found );
		return $c;
	}
}
?>
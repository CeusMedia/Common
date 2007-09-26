<?php
import( 'de.ceus-media.math.Formula' );
import( 'de.ceus-media.math.analysis.CompactInterval' );
/**
 *	Bisection Interpolation within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@uses			CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	Bisection Interpolation within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@uses			CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class Bisection
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
	 *	@param		double		tolerance		Tolerated Difference
	 *	@return		double
	 */
	function interpolate( $tolerance )
	{
		$a	= $this->_interval->getStart();
		$b	= $this->_interval->getEnd();
		$c	= false;
		while( true )
		{
			$ya	= $this->_formula->getValue( $a );
			$yb	= $this->_formula->getValue( $b );

			if( $ya * $yb > 0 )
			{
				trigger_error( "Formula has no null in Interval[".$a.",".$b."]", E_USER_WARNING );
				break;
			}
			
			$c	= ( $a + $b ) / 2;
			
			if( $b - $a <= $tolerance )
				return $c;
			$yc	= $this->_formula->getValue( $c );

			if( $ya * $yc <=0 )
				$b	= $c;
			else
				$a	= $c;
		}
		return $c;
	}
}
?>
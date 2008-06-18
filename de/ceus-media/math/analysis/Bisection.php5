<?php
import( 'de.ceus-media.math.Formula' );
import( 'de.ceus-media.math.CompactInterval' );
/**
 *	Bisection Interpolation within a compact Interval.
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
/**
 *	Bisection Interpolation within a compact Interval.
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
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
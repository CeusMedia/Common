<?php
import( 'de.ceus-media.math.Formula' );
import( 'de.ceus-media.math.CompactInterval' );
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
/**
 *	RegulaFalsi Interpolation within a compact Interval.
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@uses			Math_CompactInterval
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
class Math_Analysis_RegulaFalsi
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
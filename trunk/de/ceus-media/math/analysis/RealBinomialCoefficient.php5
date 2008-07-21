<?php
import( 'de.ceus-media.math.Factorial' );
/**
 *	Calculation of Factorial for Reals.
 *	@package		math.analysis
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2006
 *	@version		0.6
 */
/**
 *	Calculation of Factorial for Reals.
 *	@package		math.analysis
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_Analysis_RealBinomialCoefficient
{
	/**
	 *	Calculates Binomial Coefficient of Top and Button Integers.
	 *	@access		public
	 *	@param		int			$top			Top Integer
	 *	@param		int			$bottom			Bottom Integer (lower than or equal to Top Integer)
	 *	@return		int
	 */
	public static function calculate( $top, $bottom )
	{
		if( $top != (int) $top )
			throw new InvalidArgumentException( 'Top Number must be an Integer.' );
		if( $bottom != (int) $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be an Integer.' );
		else
		{
			$product	= 1;
			for( $i=0; $i<$bottom; $i++ )
				$product	*= $top - $i;
			$result	= $product / Math_Factorial::calculate( $bottom );
			return $result;
		}
	}
}
?>
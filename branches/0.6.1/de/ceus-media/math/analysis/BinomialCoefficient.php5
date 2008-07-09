<?php
import( 'de.ceus-media.math.Factorial' );
/**
 *	Calculation of Factorial for Integers.
 *	@package		math.analysis
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
/**
 *	Calculation of Factorial for Integers.
 *	@package		math.analysis
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_Analysis_BinomialCoefficient
{	
	/**
	 *	Calculates Binomial Coefficient of Top and Button Integers.
	 *	@access		public
	 *	@param		int			$top			Top Integer
	 *	@param		int			$bottom			Bottom Integer (lower than or equal to Top Integer)
	 *	@return		int
	 */
	public function calculate( $top, $bottom )
	{
		if( $top < $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be lower than or equal to Top Number.' );
		else if( $top != (int) $top )
			throw new InvalidArgumentException( 'Top Number must be an Integer.' );
		else if( $bottom != (int) $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be an Integer.' );
		else
		{
			$result	= Math_Factorial::calculate( $top ) / ( Math_Factorial::calculate( $bottom ) * Math_Factorial::calculate( $top - $bottom ) );
			return $result;
		}
	}
}
?>
<?php
import( 'de.ceus-media.math.Factorial' );
/**
 *	Calculation of Factorial for Reals.
 *	@package	math
 *	@subpackage	analysis
 *	@uses		Factorial
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		15.09.2006
 *	@version		0.1
 */
/**
 *	Calculation of Factorial for Reals.
 *	@package	math
 *	@subpackage	analysis
 *	@uses		Factorial
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		24.04.2006
 *	@version		0.1
 */
class RealBinomialCoefficient
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$this->_factorial	= new Factorial;
	}
	
	/**
	 *	Calculates Binomial Coefficient of Top and Button Integers.
	 *	@access		public
	 *	@param		int		$top			Top Integer
	 *	@param		int		$bottom		Bottom Integer (lower than or equal to Top Integer)
	 *	@return		int
	 */
	function calculate( $top, $bottom )
	{
		if( $bottom != (int) $bottom )
			trigger_error( "Bottom Number must be an Integer", E_USER_ERROR );
		else
		{
			$product	= 1;
			for( $i=0; $i<$bottom; $i++ )
				$product	*= $top - $i;
			$result	= $product / $this->_factorial->calculate( $bottom );
			return $result;
		}
	}
}
?>
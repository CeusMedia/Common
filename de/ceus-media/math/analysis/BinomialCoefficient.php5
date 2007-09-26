<?php
import( 'de.ceus-media.math.Factorial' );
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
class BinomialCoefficient
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
		if( $top < $bottom )
			trigger_error( "Bottom Number must be lower than or equal to Top Number", E_USER_ERROR );
		else if( $top != (int) $top )
			trigger_error( "Top Number must be an Integer", E_USER_ERROR );
		else if( $bottom != (int) $bottom )
			trigger_error( "Bottom Number must be an Integer", E_USER_ERROR );
		else
		{
			$result	= $this->_factorial->calculate( $top ) / ( $this->_factorial->calculate( $bottom ) * $this->_factorial->calculate( $top - $bottom ) );
			return $result;
		}
	}
}
?>
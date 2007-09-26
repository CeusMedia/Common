<?php
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
class Factorial
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}
	
	/**
	 *	Calculates Factorial of Integer recursive and returns Integer or Double.
	 *	@access		public
	 *	@param		int		$integer		Integer (<=170) to calculate Factorial for
	 *	@return		mixed
	 */
	function calculate( $integer )
	{
		if( $integer < 0 )
			trigger_error( "Factorial is defined for positive natural Numbers only", E_USER_ERROR );
		else if( $integer != (int)$integer )
			trigger_error( "Factorial is defined for natural Numbers (Integer) only", E_USER_ERROR );
		else if( $integer == 0 )
			return 1;
		else
			return $integer * $this->calculate( $integer - 1 );
		return 0;
	}
}
?>
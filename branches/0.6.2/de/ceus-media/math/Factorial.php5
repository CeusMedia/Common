<?php
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
/**
 *	Calculation of Factorial for Integers.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_Factorial
{
	/**
	 *	Calculates Factorial of Integer recursive and returns Integer or Double.
	 *	@access		public
	 *	@param		int		$integer		Integer (<=170) to calculate Factorial for
	 *	@return		mixed
	 */
	public static function calculate( $integer )
	{
		if( $integer < 0 )
			throw new InvalidArgumentException( "Factorial is defined for positive natural Numbers only" );
		else if( !is_int( $integer ) )
			throw new InvalidArgumentException( "Factorial is defined for natural Numbers (Integer) only" );
		else if( $integer == 0 )
			return 1;
		else
			return $integer * self::calculate( $integer - 1 );
		return 0;
	}
}
?>
<?php
/**
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documentation
 */
class NaturalNumbers
{
	function is_prime( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			trigger_error( "is_prime($number): first argument must be a natural number", E_USER_ERROR );
		$limit = round( sqrt( $number ) );
		$counter = 2;
		while( $counter <= $limit )
		{
			if( $number % $counter == 0 )
				return false;
			$counter ++;
		}
		return true;
	}
	
	function pow( $base, $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			trigger_error( "pow( $base, $n ): second argument must be a natural number", E_USER_ERROR );
		if( $number == 0 )
			return 1;
		else 	if( $number > 0 )
			return NaturalNumbers::pow( $base, NaturalNumbers::pre( $number ) ) * $base;		
		else if( $number < 0 )
			return NaturalNumbers::pow( NaturalNumbers::rec( $base ), NaturalNumbers::abs( $number ) );
	}

	function inv( $number )
	{
		return -1 * $number;
	}

	function abs( $number )
	{
		return max( $number, NaturalNumbers::inv( $number ) );
	}
	
	function isNatural( $number )
	{
		return fmod( $number, 1 ) == 0;
	}
	
	function fac( $number )
	{
		if( $number >= 0 )
		{
			if( $number==0 )
				return 1;
			$value = $number * NaturalNumbers::fac( NaturalNumbers::pre( $number ) );
			return $value;
		}
		return false;
	}

	function pre( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			trigger_error( "pre( $number ): first argument must be a natural number", E_USER_ERROR );
		return --$number;
	}

	function succ( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			trigger_error( "succ( $number ): first argument must be a natural number", E_USER_ERROR );
		return ++$number;
	}

	/**
	 *	maximum
	 */
	function max()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) )
			$args = $args[0];
		return max( $args );
	}

	/**
	 *	minimum
	 */
	function min()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) )
			$args = $args[0];
		return min( $args );
	}

	/**
	 *	Reciprocal
	 */
	function rec( $number )
	{
		if( $number == 0 )
			trigger_error( "rec( $number ): first argument must not be 0", E_USER_ERROR );
		return 1 / $number;
	}
	
	
	/**
	 *	Calcalates greatest common Divisor of m and n.
	 *	@access		public	 
	 *	@param		int		m		Natural Number m
	 *	@param		int		n		Natural Number n
	 *	@return		int
	 */
	function gcd( $m, $n )
	{
		if( $n != 0 )
			return NaturalNumbers::gcd( $n, $m % $n );
		else
			return $m;
	}
	
	 /**
	 *	Calculates greatest common Divisor of at least two Numbers.
	 *	@todo		Test
	 *	@todo		Code Documentation
	 */
	 function gcdm( $args )
	 {
		if( count( $args ) )
		{
			$min = $this->min( $args );
			for( $i=$min; $i>0; $i-- )
			{
				$a = true;
				foreach( $args as $arg )
					if( $arg % $i != 0 )
						$a = false;	
				if( $a )
					return $i;
			}
		}
		return false;
	}

	/**
	 *	Calculates least common Multiple of m and n.
	 *	@access		public	 
	 *	@param		int		$m		Natural Number m
	 *	@param		int		$n		Natural Number n
	 *	@return		int
	 */
	function lcm( $m, $n )
	{
		return $m * $n / NaturalNumbers::gcd( $m, $n );
	}

	/**
	 *	Calculates least common Multiple of at least 2 Numbers.
	 *	@todo		Test
	 *	@todo		Code Documentation
	 */
	 function lcmm( $args )
	 {
		if( count( $args ) )
		{
		 	$gcd = $this->gcdm( $args );
			$m = 1;
			foreach( $args as $arg )
				$m *= $arg;
			$r = $m / $gcd;
			return $r;
		}
		return false;
	 }

	/**
	 *	greatest devisor
	 */
	function greatestDivisor( $number )
	{
		$limit = round( $number / 2,0 );
		$counter = 2;
		while ($limit >= 2)
		{
			if( $number % $limit == 0 )
				return $limit;
			$limit --;
		}
		return false;
	}

	/**
	 *	least devisor
	 */
	function leastDivisor( $number )
	{
		$limit = round( sqrt( $number ) );
		$counter = 2;
		while( $counter <= $limit )
		{
			if( $number % $counter == 0 )
				return $counter;
			$counter ++;
		}
		return false;
	}
	
	function avg( $args )
	{
		return NaturalNumbers::geometricAverage( $args );
	}

	function geometricAverage( $args )
	{
		if( $size = sizeof( $args ) )
		{
			foreach( $args as $arg )
				$sum += $arg;
			$average = $sum / $size;
			return $average;
		}
		return 0;
	}
	
	function arithmeticAverage( $args )
	{
		$sum = 1;
		if( $size = sizeof( $args ) )
		{
			foreach( $args as $arg )
				$sum *= $arg;
			$average = pow( $sum, 1 / $size );
			return $average;
		}
		return $sum;
	}
}
?>
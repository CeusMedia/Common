<?php
/**
 *	@category		cmClasses
 *	@package		Math
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	@category		cmClasses
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class Math_NaturalNumbers
{
	public function abs( $number )
	{
		return max( $number, NaturalNumbers::inv( $number ) );
	}
	
	public function arithmeticAverage( $args )
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

	public function avg( $args )
	{
		return NaturalNumbers::geometricAverage( $args );
	}

	public function fac( $number )
	{
		if( $number >= 0 )
		{
			if( $number==0 )
				return 1;
			$value = $number * NaturalNumbers::fac( NaturalNumbers::pre( $number ) );
			return $value;
		}
		return 0;
	}
	
	/**
	 *	Calcalates greatest common Divisor of m and n.
	 *	@access		public	 
	 *	@param		int		m		Natural Number m
	 *	@param		int		n		Natural Number n
	 *	@return		int
	 */
	public function gcd( $m, $n )
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
	 public function gcdm( $args )
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

	public function geometricAverage( $args )
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
	
	/**
	 *	greatest devisor
	 */
	public function greatestDivisor( $number )
	{
		$limit = round( $number / 2,0 );
		while ($limit >= 2)
		{
			if( $number % $limit == 0 )
				return $limit;
			$limit --;
		}
		return false;
	}

	public function inv( $number )
	{
		return -1 * $number;
	}
	
	public function isNatural( $number )
	{
		return fmod( $number, 1 ) == 0;
	}

	public function isPrime( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			throw new InvalidArgumentException( 'First Argument must be a natural Number.' );
		$limit		= round( sqrt( $number ) );
		$counter	= 2;
		while( $counter <= $limit )
		{
			if( $number % $counter == 0 )
				return FALSE;
			$counter ++;
		}
		return TRUE;
	}

	/**
	 *	Calculates least common Multiple of m and n.
	 *	@access		public	 
	 *	@param		int		$m		Natural Number m
	 *	@param		int		$n		Natural Number n
	 *	@return		int
	 */
	public function lcm( $m, $n )
	{
		return $m * $n / NaturalNumbers::gcd( $m, $n );
	}

	/**
	 *	Calculates least common Multiple of at least 2 Numbers.
	 *	@todo		Test
	 *	@todo		Code Documentation
	 */
	 public function lcmm( $args )
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
	 *	least devisor
	 */
	public function leastDivisor( $number )
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

	/**
	 *	maximum
	 */
	public function max()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) )
			$args = $args[0];
		return max( $args );
	}

	/**
	 *	minimum
	 */
	public function min()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) )
			$args = $args[0];
		return min( $args );
	}
	
	public function pow( $base, $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			throw new InvalidArgumentException( 'First Argument must be a natural Number.' );
		if( $number == 0 )
			return 1;
		else if( $number > 0 )
			return NaturalNumbers::pow( $base, NaturalNumbers::pre( $number ) ) * $base;		
		else if( $number < 0 )
			return NaturalNumbers::pow( NaturalNumbers::rec( $base ), NaturalNumbers::abs( $number ) );
	}

	public function pre( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			throw new InvalidArgumentException( 'First Argument must be a natural Number.' );
		return --$number;
	}

	/**
	 *	Reciprocal
	 */
	public function rec( $number )
	{
		if( $number == 0 )
			trigger_error( "rec( $number ): first argument must not be 0", E_USER_ERROR );
		return 1 / $number;
	}

	public function succ( $number )
	{
		if( !NaturalNumbers::isNatural( $number ) )
			throw new InvalidArgumentException( 'First Argument must be a natural Number.' );
		return ++$number;
	}
}
?>
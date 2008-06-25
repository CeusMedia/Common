<?php
/**
 *	Prime Numbers
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.4.2005
 *	@version		0.6
 */
/**
 *	Prime Numbers
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			30.4.2005
 *	@version		0.6
 */
class Math_Prime
{
	/**
	 *	Returns all Primes from 2 to a given Number
	 *	@access		public
	 *	@param		int		$max	Greatest Number to get Primes for
	 *	@return		array
	 */
	public static function getPrimes( $max )
	{
		$primes = $numbers = array ();
		for( $i=2; $i<=$max; $i++ )
			$numbers[$i] = true;

		$edge = floor( sqrt( $max ) );
		for( $i=2; $i<=$edge; $i++ )
			if( $numbers[$i] )
				foreach( $numbers as $key => $prime )
					if( $key > $i )
						if( $prime )
							if( $key % $i == 0 )
								$numbers[$key] = false;

		foreach( $numbers as $key => $prime )
			if( $prime )
				$primes[] = $key;
		return $primes;
	}
	
	/**
	 *	Indicates whether a given Number is a Prime Number.
	 *	@access		public
	 *	@param		int		$number		Number to be checked
	 *	@return		bool
	 */
	public static function isPrime( $number )
	{
		if( $number < 2 )
			return false;
		$edge = floor( sqrt( $number ) );
		for( $i=2; $i<=$edge; $i++ )
			if( $number % $i == 0 )
				return false;
		return true;
	}
	
	/**
	 *	Returns a List of Prime Factors if given Number is dividable with Prime Numbers.
	 *	@access		public
	 *	@param		int		$number
	 *	@param		array	$list
	 *	@return		array
	 */
	public static function getPrimeFactors( $number, $list = array () )
	{
		$edge	= floor( sqrt( $number ) );
		$primes	= self::getPrimes( $edge );
		if( self::isPrime( $number ) )
		{
			$list[] = $number;
		}
		else if( count( $primes ) )
		{
			rsort( $primes );
			foreach( $primes as $prime )
			{
				if( $number % $prime == 0 )
				{
					$tmp = $list;
					$tmp[] = $prime;
					$rest = $number / $prime;
					$result = self::getPrimeFactors( $rest, $tmp );
					if( count( $result ) )
					{
						sort( $result );
						return $result;
					}
				}
			}
		}
		return $list;
	}
}
?>
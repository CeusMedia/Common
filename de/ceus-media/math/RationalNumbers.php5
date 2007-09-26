<?php
import ("de.ceus-media.math.NaturalNumbers");
/**
 *	@package	math
 *	@uses		NaturalNumbers
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@package	math
 *	@uses		NaturalNumbers
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 *	@todo		Code Documentation
 */
class RationNumbers
{

	function inv ($float)
	{
		return -1 * $float;
	}

	/**
	 * reciprocal
	 */
	function rec ($float)
	{
		if ($float == 0)
			trigger_error( "rec($float): first argument must not be 0", E_USER_ERROR );
		return 1 / $float;
	}
	
	function leastDivisor ($float, $deepth = 0)
	{
		if ($deepth > 10)
			trigger_error( "no divisor found.", E_USER_ERROR );
		if (NaturalNumbers::isNatural ($float))
			return 1;
		else
		{
			$parts = explode(".", (string)$float);
			$minor = (float) "0".".".$parts[1];
			$factor = RationNumbers::rec ($minor);
			echo "<br>[".$deepth."] minor: ".$minor." | factor: ".$factor;
			return $factor * RationNumbers::leastDivisor($factor, NaturalNumbers::succ($deepth));
		}
	}

	function getNatural ($float)
	{
		if ($float < 0)
			return (int) ceil ($float);
		else
			return (int) floor ($float);
	}

	function toFraction( $float, $deepth = 20 )
	{
		$shift	= 0;
		$values	= array();
		while( $float > 1 )
		{
			$float /= 10;
			$shift ++;
		}
		while( $float < 0.1 )
		{
			$float *= 10;
			$shift --;
		}
		for( $i=1; $i<=$deepth; $i++ )
		{
			if( $float == 0 )
				break;
			$float		= (float) $float * 10;
			$numerator	= (int) floor( $float );
			$values[$i]	= $numerator;
			if( round( $float, 1 ) == $numerator )
				break;
			$float		= $float - $numerator;
		}
		$max	= max( array_keys( $values ) );
		foreach( $values as $denominator => $numerator )
		{
			if( $max != $denominator )
				$numerator	*= pow( 10, $max ) / pow( 10, $denominator );
			$sum	+= $numerator;
		}
		$max	= pow( 10, $max );
		while( $shift > 0 )
		{
			$sum *= 10;
			$shift--;
		}
		while( $shift < 0 )
		{
			$max *= 10;
			$shift++;
		}
		if( $gcd = NaturalNumbers::gcd( $sum, $max ) )
		{
			remark( "sum: ".$sum." max: ".$max. " -> gcd: ".$gcd );
			$sum	/= $gcd;
			$max	/= $gcd;
		}
		$result	= $sum."/".$max;
		return $result;
	}
}
?>
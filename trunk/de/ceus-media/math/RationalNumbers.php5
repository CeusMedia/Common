<?php
import( 'de.ceus-media.math.NaturalNumbers' );
/**
 *	@package		math
 *	@uses			Math_NaturalNumbers
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		math
 *	@uses			Math_NaturalNumbers
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_RationNumbers
{

	public static function inv( $float )
	{
		return -1 * $float;
	}

	/**
	 * reciprocal
	 */
	public static function rec( $float )
	{
		if( $float == 0 )
			throw new InvalidArgumentException( "rec($float): first argument must not be 0" );
		return 1 / $float;
	}
	
	public static function leastDivisor( $float, $deepth = 0 )
	{
		if( $deepth > 10 )
			trigger_error( "no divisor found.", E_USER_ERROR );
		if( Math_NaturalNumbers::isNatural( $float ) )
			return 1;
		else
		{
			$parts = explode( ".", (string) $float );
			$minor = (float) "0".".".$parts[1];
			$factor = self::rec ($minor);
#			echo "<br>[".$deepth."] minor: ".$minor." | factor: ".$factor;
			return $factor * self::leastDivisor($factor, Math_NaturalNumbers::succ($deepth));
		}
	}

	public static function getNatural( $float )
	{
		if( $float < 0 )
			return (int) ceil( $float );
		else
			return (int) floor( $float );
	}

	public static function toFraction( $float, $deepth = 20 )
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
		if( $gcd = Math_NaturalNumbers::gcd( $sum, $max ) )
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
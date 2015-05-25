<?php
/**
 *	...
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
 *	@category		cmClasses
 *	@package		Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	...
 *	@category		cmClasses
 *	@package		Math
 *	@uses			Math_NaturalNumbers
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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
#			remark( "sum: ".$sum." max: ".$max. " -> gcd: ".$gcd );
			$sum	/= $gcd;
			$max	/= $gcd;
		}
		$result	= $sum."/".$max;
		return $result;
	}
}
?>
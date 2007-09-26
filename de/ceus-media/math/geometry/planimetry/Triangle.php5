<?php
/**
 *	@package	math
 *	@subpackage	geometry
 *	@extends	Planimetry
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	math
 *	@subpackage	geometry
 *	@extends	Planimetry
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@todo		Code Documentation
 */
import( 'de.ceus-media.math.geometry.planimetry.Planimetry' );
class Triangle extends Planimetry
{
	function pythagoras( $a = false, $b = false, $c = false )
	{
		if( $a && $b )
		{
			$c = sqrt( pow( $a, 2 ) + pow( $b, 2 ) );
			return $c;
		}
		else if( $c )
		{
			if( $a )
			{
				$b = sqrt( pow( $c, 2 ) - pow( $a, 2 ) );
				return $b;
			}
			else if( $b )
			{
				$a = sqrt( pow( $c, 2 ) - pow( $b, 2 ) );
				return $a;
			}
		}
	}
}
?>
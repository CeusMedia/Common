<?php
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	@package		math.geometry
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Math_Geometry_Triangle
{
	public function pythagoras( $a = false, $b = false, $c = false )
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
<?php
import ("de.ceus-media.math.algebra.Vector");
/**
 *	Cross Product of two Vectors with 3 Dimensions.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Cross Product of two Vectors with 3 Dimensions.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class VectorCrossProduct
{
	/**
	 *	Returns Cross Product of two Vectors
	 *	@access		public
	 *	@param		Vector	$vector1		Vector 1
	 *	@param		Vector	$vector2		Vector 2
	 *	@return		Vector
	 */
	function produce( $vector1, $vector2 )
	{
		if( $vector1->getDimension() != $vector2->getDimension () )
			trigger_error( "Dimensions of Vectors are not compatible", E_USER_WARNING );
		else
		{
			if( $vector1->getDimension() == 3 )
			{
				$x = $vector1->getDimValue( 2 ) * $vector2->getDimValue( 3 ) - $vector1->getDimValue( 3 ) * $vector2->getDimValue( 2 );
				$y = $vector1->getDimValue( 3 ) * $vector2->getDimValue( 1 ) - $vector1->getDimValue( 1 ) * $vector2->getDimValue( 3 );
				$z = $vector1->getDimValue( 1 ) * $vector2->getDimValue( 2 ) - $vector1->getDimValue( 2 ) * $vector2->getDimValue( 1 );
				$c = new Vector( $x, $y, $z );
			}
			return $c;
		}
	}
}
?>
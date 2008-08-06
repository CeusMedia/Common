<?php
import ("de.ceus-media.math.algebra.Vector");
/**
 *	Cross Product of two Vectors with 3 Dimensions.
 *	@package		math.algebra
 *	@uses			Math_Algebra_Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Cross Product of two Vectors with 3 Dimensions.
 *	@package		math.algebra
 *	@uses			Math_Algebra_Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_Algebra_VectorCrossProduct
{
	/**
	 *	Returns Cross Product of two Vectors
	 *	@access		public
	 *	@param		Math_Algebra_Vector		$vector1		Vector 1
	 *	@param		Math_Algebra_Vector		$vector2		Vector 2
	 *	@return		Math_Algebra_Vector
	 */
	public function produce( $vector1, $vector2 )
	{
		if( $vector1->getDimension() != $vector2->getDimension() )
			throw new Exception( 'Dimensions of Vectors are not compatible.' );
		if( $vector1->getDimension() == 3 )
		{
			$x = $vector1->getValueFromIndex( 1 ) * $vector2->getValueFromIndex( 2 ) - $vector1->getValueFromIndex( 2 ) * $vector2->getValueFromIndex( 1 );
			$y = $vector1->getValueFromIndex( 2 ) * $vector2->getValueFromIndex( 0 ) - $vector1->getValueFromIndex( 0 ) * $vector2->getValueFromIndex( 2 );
			$z = $vector1->getValueFromIndex( 0 ) * $vector2->getValueFromIndex( 1 ) - $vector1->getValueFromIndex( 1 ) * $vector2->getValueFromIndex( 0 );
			$c = new Math_Algebra_Vector( $x, $y, $z );
		}
		return $c;
	}
}
?>
<?php
/**
 *	Scalar Product of two Vectors.
 *	@package		math.algebra
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Scalar Product of two Vectors.
 *	@package		math.algebra
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_Algebra_VectorScalarProduct
{
	/**
	 *	Returns Scalar Product of two Vectors
	 *	@access		public
	 *	@param		Math_Algebra_Vector		$vector1		Vector 1
	 *	@param		Math_Algebra_Vector		$vector2		Vector 2
	 *	@return		mixed
	 */
	public function produce( $vector1, $vector2 )
	{
		$sum = 0;
		if( $vector1->getDimension() != $vector2->getDimension() )
			throw new Exception( 'Dimensions of Vectors are not compatible.' );

		for( $i=0; $i<$vector1->getDimension(); $i++)
			$sum += $vector1->getValueFromIndex( $i ) * $vector2->getValueFromIndex( $i );
		return $sum;
	}
}
?>
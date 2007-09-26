<?php
/**
 *	Scalar Product of two Vectors.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Scalar Product of two Vectors.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class VectorScalarProduct
{
	/**
	 *	Returns Scalar Product of two Vectors
	 *	@access		public
	 *	@param		Vector	$vector1		Vector 1
	 *	@param		Vector	$vector2		Vector 2
	 *	@return		mixed
	 */
	function produce ($vector1, $vector2)
	{
		$sum = 0;
		if ($vector1->getDimension() != $vector2->getDimension())
			trigger_error( "Dimensions of Vectors are not compatible", E_USER_WARNING );
		else
		{
			for ($i=0; $i<$vector1->getDimension(); $i++)
				$sum += $vector1->getValue ($i) * $vector2->getValue ($i);
		}
		return $sum;
	}
}
?>
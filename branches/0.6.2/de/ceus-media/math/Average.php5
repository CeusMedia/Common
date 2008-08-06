<?php
/**
 *	Calculates artithmetic and geometric Average.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2006
 *	@version		0.6
 */
/**
 *	Calculates artithmetic and geometric Average.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2006
 *	@version		0.6
 *	@todo			finish Implementation
 */
class Math_Average
{
	/**
	 *	Calculates artithmetic Average.
	 *	@access		public
	 *	@param		array		$values			Array of Values.
	 *	@param		int			$accuracy		Accuracy of Result 
	 *	@return		float
	 */
	public static function arithmetic( $values, $accuracy = NULL )
	{
		$sum	= 0;
		foreach( $values as $value )
			$sum	+= $value;
		$result	= $sum / count( $values );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}

	/**
	 *	Calculates geometric Average.
	 *	@access		public
	 *	@param		array		$values			Array of Values
	 *	@param		int			$accuracy		Accuracy of Result 
	 *	@return		float
	 */
	public static function geometric( $values, $accuracy = NULL )
	{
		$product	= 1;
		foreach( $values as $value )
			$product	*= $value;
		$result	= pow( $product, 1 / count( $values ) );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}
}
?>
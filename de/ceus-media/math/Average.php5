<?php
/**
 *	Calculates artithmetic and geometric Average.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			15.09.2006
 *	@version		0.1
 *	@todo			finish Implementation
 */
class Average
{
	public function __construct()
	{
	}
	
	/**
	 *	Calculates artithmetic Average.
	 *	@access		public
	 *	@param		array		$values			Array of Values.
	 *	@param		int			$accuracy		Accuracy of Result 
	 *	@return		float
	 */
	function arithmetic( $values, $accuracy = -1 )
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
	function geometric( $values, $accuracy	= -1 )
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
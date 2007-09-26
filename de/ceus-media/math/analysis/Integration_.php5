<?php
/**
 *	Integration with Trapeziod.
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 *	@deprecatd	use analysis/TrapezIntegration instead
 */
class Integration
{
	/**	@var	string		$_func		Function expression */
	var $_func;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$function		Function expression
	 *	@return		void
	 */
	public function __construct( $function = false )
	{
		if( is_object( $function ) )
			$this->setFunction( $function );
	}

	/**
	 *	Sets function expression.
	 *	@access		public
	 *	@param		string	$function		Function expression
	 *	@return		void
	 */
	function integrateTrapezoid( $start, $end, $nodes )
	{
		if( $nodes < 2 )
			trigger_error( "amount of sampling points must be > 1", E_USER_ERROR );
		$h = ( $end - $start ) / ( $nodes + 1 );
		echo "<br>h: ".$h;
		$sum = $this->_func->getValue( $start ) + $this->_func->getValue( $end );
		for( $i=1; $i<=$nodes; $i++ )
		{
			$x = $start + $i * $h;
			$y = $this->_func->getValue( $x );
			$sum += 2 * $y;
		}
		$sum = $sum * $h / 2;
		return $sum;			
	}

	/**
	 *	Sets function expression.
	 *	@access		public
	 *	@param		string	$function		Function expression
	 *	@return		void
	 */
	function setFunction( $function )
	{
		$this->_func = $function;
	}
	
	/**
	 *	Returns function expression.
	 *	@access		public
	 *	@return		void
	 */
	function getFunction()
	{
		return $this->_func;
	}
}
?>
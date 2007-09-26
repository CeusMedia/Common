<?php
/**
 *	Neville Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	Neville Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class NevilleInterpolation
{
	/**	@var	array		$_data			Array of x and y values (Xi->Fi) */
	var $_data		= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data		Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	function setData( $data )
	{
		$this->_data	= $data;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x		Value to interpolate for
	 *	@return		double
	 */
	function interpolate( $x )
	{
		$t		= array();
		$keys	= array_keys( $this->_data );
		$values	= array_values( $this->_data );
		for( $i=0; $i<count( $keys ); $i++ )
		{
			$t[$i]	= $values[$i];
			for( $j=$i-1; $j>=0; $j-- )
				$t[$j]	= $t[$j+1] + ( $t[$j+1] - $t[$j] ) * ( $x - $keys[$i] ) / ( $keys[$i] - $keys[$j] );			
		}
		return $t[0];
	}
}
?>
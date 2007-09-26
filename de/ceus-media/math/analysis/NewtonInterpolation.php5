<?php
/**
 *	Newton Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Polynomial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	Newton Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class NewtonInterpolation
{
	/**	@var	array		$_data			Array of x and y values (Xi->Fi) */
	var $_data		= array();
	/**	@var	array		$_polynomial		Polynomial coefficients */
	var $_polynomial	= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array	$data			Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	function setData( $data )
	{
		$this->_data	= $data;
	}

	/**
	 *	Build Polynomial for Interpolation.
	 *	@access		public
	 *	@return		void
	 */
	function buildPolynomial()
	{
		$t		= array();
		$a		= array();
		$keys	= array_keys( $this->_data );
		$values	= array_values( $this->_data );
		for( $i=0; $i<count( $keys ); $i++ )
		{
			$t[$i]	= $values[$i];
			for( $j=$i-1; $j>=0; $j-- )
				$t[$j]	= ( $t[$j+1] - $t[$j] ) / ( $keys[$i] - $keys[$j] );
			$a[$i]	= $t[0];
		}
		$this->_polynomial	= $a;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double	$x			Value to interpolate for
	 *	@return		double
	 */
	function interpolate( $x )
	{
		$keys	= array_keys( $this->_data );
		$n	= count( $keys );
		$p	= $this->_polynomial[$n-1];
		for( $i=$n-2; $i>=0; $i-- )
			$p	= $p * ( $x - $keys[$i] ) + $this->_polynomial[$i];
		return $p;
	}
}
?>
<?php
/**
 *	Newton Interpolation.
 *	@package		math.analysis
 *	@uses			Math_Polynomial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
/**
 *	Newton Interpolation.
 *	@package		math.analysis
 *	@uses			Math_Polynomial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
class Math_Analysis_NewtonInterpolation
{
	/**	@var		array		$data			Array of x and y values (Xi->Fi) */
	protected $data				= array();
	/**	@var		array		$polynomial		Polynomial coefficients */
	protected $polynomial		= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data			Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	public function setData( $data )
	{
		$this->data	= $data;
	}

	/**
	 *	Build Polynomial for Interpolation.
	 *	@access		public
	 *	@return		void
	 */
	public function buildPolynomial()
	{
		$t		= array();
		$a		= array();
		$keys	= array_keys( $this->data );
		$values	= array_values( $this->data );
		for( $i=0; $i<count( $keys ); $i++ )
		{
			$t[$i]	= $values[$i];
			for( $j=$i-1; $j>=0; $j-- )
				$t[$j]	= ( $t[$j+1] - $t[$j] ) / ( $keys[$i] - $keys[$j] );
			$a[$i]	= $t[0];
		}
		$this->polynomial	= $a;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x				Value to interpolate for
	 *	@return		double
	 */
	public function interpolate( $x )
	{
		$keys	= array_keys( $this->data );
		$n	= count( $keys );
		$p	= $this->polynomial[$n-1];
		for( $i=$n-2; $i>=0; $i-- )
			$p	= $p * ( $x - $keys[$i] ) + $this->polynomial[$i];
		return $p;
	}
}
?>
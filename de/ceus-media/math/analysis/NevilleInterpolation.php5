<?php
/**
 *	Neville Interpolation.
 *	@package		math.analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
/**
 *	Neville Interpolation.
 *	@package		math.analysis
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.6
 */
class Math_Analysis_NevilleInterpolation
{
	/**	@var		array		$data		Array of x and y values (Xi->Fi) */
	protected $data				= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data		Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	protected function setData( $data )
	{
		$this->data	= $data;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x			Value to interpolate for
	 *	@return		double
	 */
	protected function interpolate( $x )
	{
		$t		= array();
		$keys	= array_keys( $this->data );
		$values	= array_values( $this->data );
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
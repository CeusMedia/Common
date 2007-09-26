<?php
import( 'de.ceus-media.math.Formula' );
/**
 *	Lagrange Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
/**
 *	Lagrange Interpolation.
 *	@package		math
 *	@subpackage		analysis
 *	@uses			Formula
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			03.02.2006
 *	@version		0.1
 */
class LagrangeInterpolation
{
	/**	@var	array		$_data			Array of x and y values (Xi->Fi) */
	var $_data		= array();
	/**	@var	array		$_expressions		Array of built Expressions */
	var $_expressions	= array();	

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
	 *	Build Expressions for Interpolation.
	 *	@access		public
	 *	@return		void
	 */
	function buildExpressions()
	{
		$this->_expressions	= array();
		$values	= array_keys( $this->_data );
		for( $i=0; $i<count( $values ); $i++ )
		{
			$this->_expressions[$i]	= "";
			for( $k=0; $k<count( $values ) ;$k++ )
			{
				if( $k == $i )
					continue;
				$expression	= "(x-".$values[$k].")/(".$values[$i]."-".$values[$k].")";
				if( strlen( $this->_expressions[$i] ) )
					$this->_expressions[$i]	.= "*".$expression;
				else
					$this->_expressions[$i]	= $expression;
			}
		}
	}

	/**
	 *	Returns built Expression.
	 *	@access		public
	 *	@return		array
	 */
	function getExpressions()
	{
		return $this->_expressions;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x		Value to interpolate for
	 *	@return		double
	 */
	function interpolate( $x )
	{
		$sum	= 0;
		$values	= array_values( $this->_data );
		$expressions	= $this->getExpressions();
		for( $i=0; $i<count( $expressions ); $i++ )
		{
			$expression	= $expressions[$i];
			$formula	= new Formula( $expression, array( "x" ) );
			$value	= $formula->getValue( 2 );
			$sum	+= $values[$i] * $value;
		}
		return $sum;
	}
	
	function neville( $x )
	{
		$t		= array();
		$keys	= array_keys( $this->_data );
		$values	= array_values( $this->_data );
		for( $i=0; $i< count( $keys ); $i++ )
		{
			$t[$i]	= $values[$i];
			for( $j=$i-1; $j>=0; $j-- )
			{
				$t[$j]	= $t[$j+1] + ( $t[$j+1] - $t[$j] ) * ( $x - $keys[$i] ) / ( $keys[$i] - $keys[$j] );			
			}
		}
		return $t[0];
	}
}
?>
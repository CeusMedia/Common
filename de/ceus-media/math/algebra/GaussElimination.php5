<?php
import( 'de.ceus-media.math.algebra.Vector' );
/**
 *	Randomizer supporting different sign types.
 *	@package		math
 *	@subpackage		algebra
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.01.2006
 *	@version		0.1
 */
/**
 *	Randomizer supporting different sign types.
 *	@package		math
 *	@subpackage		algebra
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.01.2006
 *	@version		0.1
 */
class GaussElimination
{
	/**	@var	int		$accuracy		Accuracy of calculation */
	var	$_accuracy;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$accuracy	Accuracy of calculation
	 *	@return		void
	 */
	public function __construct( $accuracy )
	{
		$this->_accuracy	= $accuracy;
	}
	
	/**
	 *	Returns the advices Privot Row within a Matrix.
	 *	@access		public
	 *	@param		Matrix	$matrix		Matrix to eliminate
	 *	@param		int		$column		current Column
	 *	@param		int		$row		Row to start Search
	 *	@return		int
	 */
	function _findPivotRow( $matrix, $column, $row = 0 )
	{
		$r	= $row;
		$a	= abs( $matrix->getValue( $row, $column ) );
		for( $i=$row+1; $i<$matrix->getDimX(); $i++ )
		{
			if( abs( $matrix->getValue( $i, $column ) ) > $a )
			{
				$a	= abs( $matrix->getValue( $i, $column ) );
				$r	= $i;
			}
		}
		return $r;
	}

	/**
	 *	Eliminates Matrix using Gauss Algorithm.
	 *	@access		public
	 *	@param		Matrix	$matrix		Matrix to eliminate
	 *	@return		Matrix
	 */
	function eliminate( $matrix )
	{
		$lines	= $matrix->getDimX();
		for( $i=0; $i<$lines-1; $i++ )
		{
			$r	= $this->_findPivotRow( $matrix, $i, $i );
			if( $i != $r )
				$matrix->swapRows( $r, $i );
			for( $j=$i+1; $j<$lines; $j++ )
			{
				$f	= $matrix->getValue( $j, $i ) / $matrix->getValue( $i, $i );
				for( $k=$i; $k<$matrix->getDimY(); $k++ )
				{
					$value	= $matrix->getValue( $j, $k ) - $f * $matrix->getValue( $i, $k );
					$matrix->setValue( $j, $k, $value );
				}
			}
		}
		return $matrix;
	}
	
	/**
	 *	Resolves eliminated Matrix and return Solution Vector.
	 *	@access		public
	 *	@param		Matrix	$matrix		Matrix to eliminate
	 *	@return		Vector
	 */
	function resolve( $matrix )
	{
		$lines	= $matrix->getDimX();
		$solution	= array();
		for( $i=$lines-1; $i>=0; $i-- )
		{
			$line	= $matrix->get[$i];
			for( $j=$lines-1; $j>=0; $j-- )
			{
				if( isset( $solution[$j] ) )
				{
					$var	= $solution[$j];
					$value	= $matrix->getValue( $i, $matrix->getDimY()-1 ) - $var * $matrix->getValue( $i, $j );
					$matrix->setValue( $i, $matrix->getDimY()-1, $value );
				}
				else
				{
					$factor	= $matrix->getValue( $i, $j );
					$value	= $matrix->getValue( $i, $matrix->getDimY()-1 );
					$var		= $value / $factor;
					$solution[$j]	= $var;
					$solution[$j]	= round( $var, $this->_accuracy );
					break;
				}
			}
		}
		ksort( $solution );
		$solution	= new Vector( array_values( $solution ) );
		return $solution;
	}
}
?>
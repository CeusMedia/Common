<?php
/**
 *	Implementation of Gauss Eleminiation Algorith with Pivot Search.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Math.Algebra
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2006
 *	@version		$Id$
 */
/**
 *	Implementation of Gauss Eleminiation Algorith with Pivot Search.
 *	@category		cmClasses
 *	@package		Math.Algebra
 *	@uses			Math_Algebra_Vector
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.01.2006
 *	@version		$Id$
 */
class Math_Algebra_GaussElimination
{
	/**	@var	int		$accuracy		Accuracy of calculation */
	protected	$accuracy;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$accuracy	Accuracy of calculation
	 *	@return		void
	 */
	public function __construct( $accuracy )
	{
		$this->accuracy	= $accuracy;
	}
	
	/**
	 *	Returns the advices Privot Row within a Matrix.
	 *	@access		protected
	 *	@param		Math_Algebra_Matrix		$matrix		Matrix to eliminate
	 *	@param		int						$column		current Column
	 *	@param		int						$row		Row to start Search
	 *	@return		int
	 */
	protected function findPivotRow( $matrix, $column, $row = 0 )
	{
		$r	= $row;
		$a	= abs( $matrix->getValue( $row, $column ) );
		for( $i=$row+1; $i<$matrix->getRowNumber(); $i++ )
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
	 *	@param		Math_Algebra_Matrix		$matrix		Matrix to eliminate
	 *	@return		Math_Algebra_Matrix
	 */
	public function eliminate( $matrix )
	{
		$lines	= $matrix->getRowNumber();
		for( $i=0; $i<$lines-1; $i++ )
		{
			$r	= $this->findPivotRow( $matrix, $i, $i );
			if( $i != $r )
				$matrix->swapRows( $r, $i );
			for( $j=$i+1; $j<$lines; $j++ )
			{
				$f	= $matrix->getValue( $j, $i ) / $matrix->getValue( $i, $i );
				for( $k=$i; $k<$matrix->getColumnNumber(); $k++ )
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
	 *	@param		Math_Algebra_Matrix		$matrix		Matrix to eliminate
	 *	@return		Math_Algebra_Vector
	 */
	public function resolve( $matrix )
	{
		$lines	= $matrix->getRowNumber();
		$solution	= array();
		for( $i=$lines-1; $i>=0; $i-- )
		{
			for( $j=$lines-1; $j>=0; $j-- )
			{
				if( isset( $solution[$j] ) )
				{
					$var	= $solution[$j];
					$value	= $matrix->getValue( $i, $matrix->getColumnNumber()-1 ) - $var * $matrix->getValue( $i, $j );
					$matrix->setValue( $i, $matrix->getColumnNumber()-1, $value );
				}
				else
				{
					$factor	= $matrix->getValue( $i, $j );
					$value	= $matrix->getValue( $i, $matrix->getColumnNumber()-1 );
					$var		= $value / $factor;
					$solution[$j]	= $var;
					$solution[$j]	= round( $var, $this->accuracy );
					break;
				}
			}
		}
		ksort( $solution );
		$solution	= new Math_Algebra_Vector( array_values( $solution ) );
		return $solution;
	}
}
?>
<?php
/**
 *	...
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		math.algebra
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
import( 'de.ceus-media.math.algebra.Matrix' );
/**
 *	...
 *	@package		math.algebra
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			Code Doc
 */
class Math_Algebra_LabelMatrix
{
	public function __construct( $rows, $columns, $init = 0 )
	{
		$this->rows		= $rows;
		$this->columns	= $columns;
		$this->matrix	= new Math_Algebra_Matrix( count( $rows ), count( $columns ), $init );
	}

	public function __call( $method, $arguments )
	{
#		remark( "method: ".$method );
		if( !method_exists( $this->matrix, $method ) )
			throw new BadMethodCallException( 'Method "'.$method.'" is not existing.' );
		return call_user_func_array( array( &$this->matrix, $method ), $arguments );
	}

	public function getColumnIndex( $column )
	{
		if( !in_array( $column, $this->columns ) )
			throw new InvalidArgumentException( 'Column "'.$column.'" is not existing.' );
		return array_search( $column, $this->columns );
	}

	public function getRowIndex( $row )
	{
		if( !in_array( $row, $this->rows ) )
			throw new InvalidArgumentException( 'Row "'.$row.'" is not existing.' );
		return array_search( $row, $this->rows );
	}
	
	public function getColumn( $column )
	{
		if( is_string( $column ) )
			$column	= $this->getColumnIndex( $column );
		return array_combine( $this->rows, $this->matrix->getColumn( $column )->toArray() );
	}
	
	public function getRow( $row )
	{
		if( is_string( $row ) )
			$row	= $this->getRowIndex( $row );
		return array_combine( $this->columns, $this->matrix->getRow( $row )->toArray() );
	}
	
	public function getValue( $row, $column )
	{
		if( is_string( $row ) )
			$row	= $this->getRowIndex( $row );
		if( is_string( $column ) )
			$column	= $this->getColumnIndex( $column );
		return $this->matrix->getValue( $row, $column );
	}
	
	public function setValue( $row, $column, $value )
	{
		if( is_string( $row ) )
			$row	= $this->getRowIndex( $row );
		if( is_string( $column ) )
			$column	= $this->getColumnIndex( $column );
		return $this->matrix->setValue( $row, $column, $value );
	}
	
	public function swapColumns( $column1, $column2 )
	{
		if( is_string( $column1 ) )
			$column1	= $this->getColumnIndex( $column1 );
		if( is_string( $column2 ) )
			$column2	= $this->getColumnIndex( $column2 );
		$this->matrix->swapColumns( $column1, $column2 );
	}
	
	public function swapRows( $row1, $row2 )
	{
		if( is_string( $row1 ) )
			$row1	= $this->getRowIndex( $row1 );
		if( is_string( $row2) )
			$row2	= $this->getRowIndex( $row2 );
		$this->matrix->swapRows( $row1, $row2 );
	}

	public function transpose()
	{
		$rows		= $this->rows;
		$columns	= $this->columns;
		$this->matrix->transpose();
		$this->rows		= $columns;
		$this->columns	= $rows;
	}
	
	public function toArray()
	{
		$list	= array();
		foreach( $this->rows as $row )
			$list[$row]	= $this->getRow( $row );
		return $list;
	}
}
?>
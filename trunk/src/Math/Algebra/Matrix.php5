<?php
/**
 *	Matrix.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Matrix.
 *	@category		cmClasses
 *	@package		Math.Algebra
 *	@uses			Math_Algebra_Vector
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Math_Algebra_Matrix
{
	/**	@var		int			$rowNumber		Number of Rows */
	protected $rowNumber		= 0;
	/**	@var		int			$columnNumber	Number of Columns */
	protected $columnNumber		= 0;
	/**	@var		array		$values			Values of Matrix */
	protected $values			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$rowNumber		Number of Rows
	 *	@param		int			$columnNumber	Number of Columns
	 *	@param		int			$init			Initial Values in Matrix
	 *	@return		void
	 */
	public function __construct( $rowNumber, $columnNumber, $init = 0 )
	{
		if( $rowNumber < 1 )
			throw new InvalidArgumentException( 'Number of Rows must be at least 1.' );
		if( $columnNumber < 1 )
			throw new InvalidArgumentException( 'Number of Columns must be at least 1.' );
		$this->rowNumber	= $rowNumber;
		$this->columnNumber	= $columnNumber;
		$this->clear( $init );
	}
	
	/**
	 *	Clears Matrix by setting initial value.
	 *	@access		public
	 *	@param		int			$init			initial values in Matrix
	 *	@return		void
	 */
	public function clear( $init = 0 )
	{
		for( $row = 0; $row < $this->getRowNumber(); $row++ )
			for( $column = 0; $column < $this->getColumnNumber(); $column++ )
				$this->setValue( $row, $column, $init );
	}
	
	/**
	 *	Returns Number of Rows.
	 *	@access		public
	 *	@return		int
	 */
	public function getRowNumber()
	{
		return $this->rowNumber;
	}

	/**
	 *	Returns Number of Columns.
	 *	@access		public
	 *	@return		int
	 */
	public function getColumnNumber()
	{
		return $this->columnNumber;
	}

	/**
	 *	Returns a column as Vector.
	 *	@access		public
	 *	@param		int			$column			Column Index
	 *	@return		Math_Algebra_Vector
	 */
	public function getColumn( $column )
	{
		if( $column < 0 || $column >= $this->getColumnNumber() )
			throw new OutOfRangeException( 'Column key "'.$column.'" is not valid.' );
		$values = array();
		for( $row = 0; $row < $this->getRowNumber(); $row++ )
			$values[] = $this->getValue( $row, $column );
		return new Math_Algebra_Vector( $values );
	}

	/**
	 *	Returns a row as Vector.
	 *	@access		public
	 *	@param		int			$row			Row Index
	 *	@return		Math_Algebra_Vector
	 */
	public function getRow( $row )
	{
		if( $row < 0 || $row >= $this->getRowNumber() )
			throw new OutOfRangeException( 'Row key "'.$row.'" is not valid.' );
		return new Math_Algebra_Vector( $this->values[$row] );
	}
	
	/**
	 *	Returns a Value.
	 *	@access		public
	 *	@param		int			$row			Row Index
	 *	@param		int			$column			Column Index
	 *	@return		mixed
	 */
	public function getValue( $row, $column )
	{
		if( $row < 0 || $row >= $this->getRowNumber() )
			throw new OutOfRangeException( 'Row key "'.$row.'" is not valid.' );
		if( $column < 0 || $column >= $this->getColumnNumber() )
			throw new OutOfRangeException( 'Column key "'.$column.'" is not valid.' );
		return $this->values[$row][$column];
	}

	/**
	 *	Sets a value.
	 *	@access		public
	 *	@param		int			$row			Row Index
	 *	@param		int			$column			Column Index
	 *	@param		mixed		$value			Values to be set
	 *	@return		void
	 */
	public function setValue( $row, $column, $value )
	{
		if( $row < 0 || $row >= $this->getRowNumber() )
			throw new OutOfRangeException( 'Row key "'.$row.'" is not valid.' );
		if( $column < 0 || $column >= $this->getColumnNumber() )
			throw new OutOfRangeException( 'Column key "'.$column.'" is not valid.' );
		$this->values[$row][$column] = $value;
	}

	/**
	 *	Returns transposed Matrix.
	 *	@access		public
	 *	@return		Math_Algebra_Matrix
	 */
	public function transpose()
	{
		$array	= array();
		$rowNumber		= $this->getRowNumber();
		$columnNumber	= $this->getColumnNumber();
		for( $row = 0; $row < $rowNumber; $row++ )
			for( $column = 0; $column < $columnNumber; $column++ )
				$array[$column][$row]	= $this->values[$row][$column];
		$this->rowNumber	= $columnNumber;
		$this->columnNumber	= $rowNumber;
		$this->values		= $array;
	}

	/**
	 *	Swaps 2 Rows within Matrix.
	 *	@access		public
	 *	@param		int			$row1			Index of Source Row 
	 *	@param		int			$row2			Index of Target Row 
	 *	@return		void
	 */
	public function swapRows( $row1, $row2 )
	{
		if( $row1 < 0 || $row1 >= $this->getRowNumber() )
			throw new OutOfRangeException( 'Source Row key "'.$row1.'" is not valid.' );
		if( $row2 < 0 || $row2 >= $this->getRowNumber() )
			throw new OutOfRangeException( 'Target Row key "'.$row2.'" is not valid.' );

		$buffer	= $this->values[$row2];
		$this->values[$row2]	= $this->values[$row1];
		$this->values[$row1]	= $buffer;
	}
	
	/**
	 *	Swaps 2 Columns within Matrix.
	 *	@access		public
	 *	@param		int			$column1		Index of Source Column
	 *	@param		int			$column2		Index of Target Column
	 *	@return		void
	 */
	public function swapColumns( $column1, $column2 )
	{
		if( $column1 < 0 || $column1 >= $this->getColumnNumber() )
			throw new OutOfRangeException( 'Column key "'.$column1.'" is not valid.' );
		if( $column2 < 0 || $column2 >= $this->getColumnNumber() )
			throw new OutOfRangeException( 'Column key "'.$column2.'" is not valid.' );

		for( $row = 0; $row < $this->getRowNumber(); $row++ )
		{
			$buffer	= $this->values[$row][$column1];
			$this->values[$row][$column1]	= $this->values[$row][$column2];
			$this->values[$row][$column2]	= $buffer;
		}
	}
	
	/**
	 *	Returns Matrix as Array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->values;		
	}
	
	/**
	 *	Returns Matrix as HTML Table.
	 *	@access		public
	 *	@return		string
	 */
/*	public function toTable()
	{
		$code = "<table style='border-width: 0px 1px 0px 1px; border-style: solid; border-color: black'>";
		for( $row = 0; $row < $this->getRowNumber(); $row++ )
		{
			$code .= "<tr>";
			for( $column = 0; $column < $this->getColumnNumber(); $column++ )
				$code .= "<td align='right'>".$this->getValue( $row, $column )."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";
		return $code;
	}*/
}
?>
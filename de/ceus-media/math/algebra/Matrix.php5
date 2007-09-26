<?php
import( 'de.ceus-media.math.algebra.Vector' );
/**
 *	Matrix.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Matrix.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Matrix
{
	/**	@var	int		$_dimx		Dimension of x axis */
	var $_dimx = 0;
	/**	@var	int		$_dimy		Dimension of y axis */
	var $_dimy = 0;
	/**	@var	array	$_values		Values of Matrix */
	var $_values = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int		$dimx		Dimension of x axis
	 *	@param		int		$dimy		Dimension of y axis
	 *	@param		int		$init			initial values in Matrix
	 *	@return		void
	 */
	public function __construct( $dimx, $dimy, $init = 0 )
	{
		if( $dimx < 1 )
			trigger_error( "Dimension of x axis must be at least 1.", E_USER_ERROR );
		if( $dimx < 1 )
			trigger_error( "Dimension of y axis must be at least 1.", E_USER_ERROR );
		$this->_dimx = $dimx;
		$this->_dimy = $dimy;
		$this->clear( $init );
	}
	
	/**
	 *	Clears Matrix by setting initial value.
	 *	@access		public
	 *	@param		int		$init			initial values in Matrix
	 *	@return		void
	 */
	function clear( $init = 0 )
	{
		for( $x = 0; $x < $this->getDimX(); $x++ )
			for( $y = 0; $y < $this->getDimY(); $y++ )
				$this->setValue( $x, $y, $init );
	}
	
	/**
	 *	Returns Dimension of x axis.
	 *	@access		public
	 *	@return		int
	 */
	function getDimX()
	{
		return $this->_dimx;
	}

	/**
	 *	Returns Dimension of y axis.
	 *	@access		public
	 *	@return		int
	 */
	function getDimY()
	{
		return $this->_dimy;
	}

	/**
	 *	Returns a column as Vector.
	 *	@access		public
	 *	@param		int		$column		Column Key on y axis
	 *	@return		Vector
	 */
	function getColumn( $column )
	{
		if( $column < 0 || $column >= $this->getDimY() )
			trigger_error( "Column key '".$column."' is not valid.", E_USER_ERROR );
		$values = array();
		for( $x = 0; $x < $this->getDimX(); $x++ )
			$values[] = $this->getValue( $x, $column );
		$v = new Vector( $values );
		return $v;
	}

	/**
	 *	Returns a row as Vector.
	 *	@access		public
	 *	@param		int		$row		Row Key on x axis
	 *	@return		Vector
	 */
	function getRow( $row )
	{
		if( $row < 0 || $row >= $this->getDimX() )
			trigger_error( "Row key '".$row."' is not valid.", E_USER_ERROR );
		$v = new Vector( $this->_values[$row] );
		return $v;
	}
	
	/**
	 *	Returns a Value.
	 *	@access		public
	 *	@param		int		$x			Key on x axis
	 *	@param		int		$y			Key on y axis
	 *	@return		mixed
	 */
	function getValue( $x, $y )
	{
		if( $x < 0 || $x >= $this->getDimX() )
			trigger_error( "Row key '".$x."' is not valid.", E_USER_ERROR );
		if( $y < 0 || $y >= $this->getDimY() )
			trigger_error( "Column key '".$y."' is not valid.", E_USER_ERROR );
		return $this->_values[$x][$y];
	}

	/**
	 *	Sets a value.
	 *	@access		public
	 *	@param		int		$x			Key on x axis
	 *	@param		int		$y			Key on y axis
	 *	@param		mixed	$value		Values to be set
	 *	@return		void
	 */
	function setValue ($x, $y, $value)
	{
		if( $x < 0 || $x >= $this->getDimX() )
			trigger_error( "Row key '".$x."' is not valid.", E_USER_ERROR );
		if( $y < 0 || $y >= $this->getDimY() )
			trigger_error( "Column key '".$y."' is not valid.", E_USER_ERROR );
		$this->_values[$x][$y] = $value;
	}

	/**
	 *	Returns transposed Matrix.
	 *	@access		public
	 *	@return		Matrix
	 */
	function transpose()
	{
		$m = new Matrix( $this->getDimY(), $this->getDimX() );
		for( $x = 0; $x < $this->getDimX(); $x++ )
			for( $y = 0; $y < $this->getDimY(); $y++ )
				$m->setValue( $y, $x, $this->getValue( $x, $y ) );
		return $m;
	}

	/**
	 *	Swaps 2 Rows within Matrix.
	 *	@access		public
	 *	@param		int		$row1		Source Row 
	 *	@param		int		$row2		Target Row 
	 *	@return		void
	 */
	function swapRows( $row1, $row2 )
	{
		for( $i=0; $i<$this->getDimY(); $i++ )
		{
			$buffer	= $this->getValue( $row1, $i );
			$this->setValue( $row1, $i, $this->getValue( $row2, $i ) );
			$this->setValue( $row2, $i, $buffer );
		}
	}
	
	/**
	 *	Swaps 2 Columns within Matrix.
	 *	@access		public
	 *	@param		int		$col1		Source Column
	 *	@param		int		$col2		Target Column
	 *	@return		void
	 */
	function swapColumns( $col1, $col2 )
	{
		for( $i=0; $i<$this->getDimX(); $i++ )
		{
			$buffer	= $this->getValue( $i, $col1 );
			$this->setValue( $i, $col1, $this->getValue( $i, $col2 ) );
			$this->setValue( $i, $col2, $buffer );
		}
	}
	
	/**
	 *	Returns Matrix as Array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		return $this->_values;		
	}
	
	/**
	 *	Returns Matrix as HTML Table.
	 *	@access		public
	 *	@return		string
	 */
	function toTable()
	{
		$code = "<table style='border-width: 0px 1px 0px 1px; border-style: solid; border-color: black'>";
		for( $x = 0; $x < $this->getDimX(); $x++ )
		{
			$code .= "<tr>";
			for( $y = 0; $y < $this->getDimY(); $y++ )
				$code .= "<td align='right'>".$this->getValue( $x, $y )."</td>";
			$code .= "</tr>";
		}
		$code .= "</table>";
		return $code;
	}
}
?>
<?php
import( 'de.ceus-media.math.algebra.Vector' );
/**
 *	KeyMatrix.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	KeyMatrix.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@uses			Vector
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class KeyMatrix
{
	/**	@var	array	$_keys_x		Keys of x axis */
	var $_keys_x;
	/**	@var	array	$_keys_y		Keys of y axis */
	var $_keys_y;
	/**	@var	array	$_values		Values of Matrix */
	var $_values = array();
	
	
	public function __construct( $keys_x, $keys_y, $init = 0 )
	{
		$this->_keys_x = $keys_x;
		$this->_keys_y = $keys_y;
		$this->clear( $init );
	}
	
	/**
	 *	Clears Matrix by setting initial value.
	 *	@access		public
	 *	@param		int		$init		initial values in Matrix
	 *	@return		void
	 */
	function clear( $init = 0 )
	{
		foreach( $this->getKeysX() as $key_x )
			foreach( $this->getKeysY() as $key_y )
				$this->setValue( $key_x, $key_y, $init );
	}
	
	/**
	 *	Returns Dimension of x axis.
	 *	@access		public
	 *	@return		int
	 */
	function getDimX()
	{
		return count( $this->_keys_x );
	}

	/**
	 *	Returns Dimension of y axis.
	 *	@access		public
	 *	@return		int
	 */
	function getDimY()
	{
		return count ($this->_keys_y);
	}

	/**
	 *	Returns Keys of x axis.
	 *	@access		public
	 *	@return		array
	 */
	function getKeysX()
	{
		return $this->_keys_x;
	}

	/**
	 *	Returns Keys of y axis.
	 *	@access		public
	 *	@return		array
	 */
	function getKeysY()
	{
		return $this->_keys_y;
	}

	/**
	 *	Returns a column as Vector.
	 *	@access		public
	 *	@param		int		$column		Column Key on y axis
	 *	@return		Vector
	 */
	function getColumn( $column )
	{
		if( !in_array( $column, $this->getKeysY() ) )
			trigger_error( "Column key '".$y."' is not valid.", E_USER_ERROR );
		$values = array();
		foreach( $this->getKeysX() as $key_x)
			$values[$key_x] = $this->getValue( $key_x, $column );
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
		if( !in_array( $row, $this->getKeysX() ) )
			trigger_error( "Row key '".$row."' is not valid.", E_USER_ERROR );
		$v = new Vector( $this->_values[$row] );
		return $v;
	}
	
	/**
	 *	Returns a Value.
	 *	@access		public
	 *	@param		int		$x		Key on x axis
	 *	@param		int		$y		Key on y axis
	 *	@return		mixed
	 */
	function getValue( $x, $y )
	{
		if( !in_array( $x, $this->getKeysX() ) )
			trigger_error( "Row key '".$x."' is not valid.", E_USER_ERROR );
		if( !in_array( $y, $this->getKeysY() ) )
			trigger_error( "Column key '".$y."' is not valid.", E_USER_ERROR );
		return $this->_values[$x][$y];
	}

	/**
	 *	Sets a value.
	 *	@access		public
	 *	@param		int		$x		Key on x axis
	 *	@param		int		$y		Key on y axis
	 *	@param		mixed	$value	Values to be set
	 *	@return		void
	 */
	function setValue( $x, $y, $value )
	{
		if( !in_array( $x, $this->getKeysX() ) )
			trigger_error( "Row key '".$x."' is not valid.", E_USER_ERROR );
		if( !in_array( $y, $this->getKeysY() ) )
			trigger_error( "Column key '".$y."' is not valid.", E_USER_ERROR );
		$this->_values[$x][$y] = $value;
	}

	/**
	 *	Returns transposed Matrix.
	 *	@access		public
	 *	@return		KeyMatrix
	 */
	function transpose()
	{
		$km = new KeyMatrix ($this->getKeysY(), $this->getKeysX());
		foreach ($this->getKeysX() as $key_x)
			foreach ($this->getKeysY() as $key_y)
				$km->setValue ($key_y, $key_x, $this->getValue ($key_x, $key_y));
		return $km;
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
		$code = "
<table border='0' cellspacing='0' cellpadding='0'>
  <tr><th></th><th>".implode( "</th><th>", $this->getKeysX() )."</th></tr>
  <tr><td>
    <table><tr><th>".implode( "</th></tr><tr><th>", $this->getKeysY() )."</th></tr></table>
  </td><td colspan='".count( $this->getKeysX() )."'>";

		$code .= "<table style='border-width: 0px 1px 0px 1px; border-style: solid; border-color: black'>";
		foreach( $this->getKeysX() as $key_x )
		{
			$code .= "<tr>";
			foreach( $this->getKeysY() as $key_y )
				$code .= "<td>".$this->getValue( $key_x, $key_y )."</td>";
			$code .= "</tr>";
		}
		$code .= "</table></td></tr></table>";
		return $code;
	}
}
?>
<?php
/**
 *	Vector.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Vector.
 *	@package		math
 *	@subpackage		algebra
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class Vector
{
	/**	@var	int		$_dim		Dimension of the Vector */
	var $_dim = 0;
	/**	@var	array	$_vals	Value of the Vector */
	var $_vals = array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$args = func_get_args();
		if( is_array( $args[0] ) )
			$args = $args[0];
//		if (!count ($args))
//			trigger_error( "Vector needs Arguments", E_USER_ERROR );
		$this->_setDimension( count( $args ) );
		$this->_setValues( $args );
	}
	
	/**
	 *	Adds a Value to Vector and increases Dimension
	 *	@access		public
	 *	@param		mixed	$value		Value to add
	 *	@return		void
	 */
	function addValue( $value )
	{
		$this->_vals[]	= $value;
		$this->_dim++;
	}
	
	/**
	 *	Returns the dimension of the Vector.
	 *	@access		public
	 *	@return		int
	 */
	function getDimension()
	{
		return $this->_dim;
	}
	
	/**
	 *	Returns the value of a dimension.
	 *	@access		public
	 *	@param		int		$index		Dimension starting with 1
	 *	@return		mixed
	 */
	function getDimValue( $index )
	{
		return $this->getValue( $index-1 );
	}

	/**
	 *	Returns the value of a dimension starting with 0.
	 *	@access		public
	 *	@param		int		$index		Dimension starting with 0
	 *	@return		mixed
	 */
	function getValue( $index )
	{
		$val = 0;
		if( $index >= ( $dim = $this->getDimension() ) )
			trigger_error( "Vector Index(".$index.") cannot be larger than Vector Dimension (".$dim.")", E_USER_WARNING );
		else
			$val = $this->_vals[$index];
		return $val;
	}

	/**
	 *	Sets the dimension of the Vector.
	 *	@access		private
	 *	@param		int		$dim			Dimension to be set
	 *	@return		void
	 */
	function _setDimension( $dim )
	{
		$this->_dim = $dim;
	}

	/**
	 *	Sets the values of all dimension of the Vector.
	 *	@access		public
	 *	@param		array	$values		Values of all dimension
	 *	@return		void
	 */
	function _setValues( $vals )
	{
		$this->_vals = $vals;
	}
	
	/**
	 *	Returns Vector as array.
	 *	@access		public
	 *	@return		array
	 */
	function toArray()
	{
		return $this->_vals;
	}
	
	/**
	 *	Returns Vector as a representative string.
	 *	@access		public
	 *	@return		string
	 */
	function toString()
	{
		$code = "(".implode( ", ", array_values( $this->_vals ) ).")";
		return $code;
	
	}
}
?>
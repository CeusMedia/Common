<?php
/**
 *	Vector.
 *	@package		math.algebra
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Vector.
 *	@package		math.algebra
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_Algebra_Vector
{
	/**	@var		int			$dimension		Dimension of the Vector */
	protected $dimension		= 0;
	/**	@var		array		$values			Value of the Vector */
	protected $values			= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
		$arguments = func_get_args();
		if( is_array( $arguments[0] ) )
			$arguments = $arguments[0];
		if( !count( $arguments ) )
			throw new InvalidArgumentException( 'Vector needs at least 1 Value.' );
		foreach( $arguments as $argument )
			$this->addValue( $argument );
	}
	
	/**
	 *	Returns Vector as a representative string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$code = "(".implode( ", ", array_values( $this->values ) ).")";
		return $code;
	}
	
	/**
	 *	Adds a Value to Vector and increases Dimension
	 *	@access		public
	 *	@param		mixed		$value			Value to add
	 *	@return		void
	 */
	public function addValue( $value )
	{
		$value	= is_int( $value ) ? $value : (float) $value;
		$this->values[]	= $value;
		$this->dimension++;
	}
	
	/**
	 *	Returns the dimension of the Vector.
	 *	@access		public
	 *	@return		int
	 */
	public function getDimension()
	{
		return $this->dimension;
	}
	
	/**
	 *	Returns the value of a dimension.
	 *	@access		public
	 *	@param		int			$dimension		Dimension starting with 1
	 *	@return		mixed
	 */
	public function getValueFromDimension( $dimension )
	{
		return $this->getValueFromIndex( $dimension - 1 );
	}

	/**
	 *	Returns the value of a dimension starting with 0.
	 *	@access		public
	 *	@param		int			$index			Dimension starting with 0
	 *	@return		mixed
	 */
	public function getValueFromIndex( $index )
	{
		$dimension	= $this->getDimension();
		if( $index < 0 )
			throw new OutOfRangeException( 'Vector Index ('.$index.') must be greater than 0.' );
		if( $index >= $dimension )
			throw new OutOfRangeException( 'Vector Index ('.$index.') must be lower than Vector Dimension ('.$dimension.').' );
		return $this->values[$index];
	}
	
	/**
	 *	Returns Vector as array.
	 *	@access		public
	 *	@return		array
	 */
	public function toArray()
	{
		return $this->values;
	}
}
?>
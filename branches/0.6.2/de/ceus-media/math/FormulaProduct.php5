<?php
/**
 *	Resolution of Formula Products within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
/**
 *	Resolution of Formula Products within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_FormulaProduct
{
	/**	@var		Math_Formula			$formula		Formula */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval */
	protected $interval;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula within Product
	 *	@param		Math_CompactInterval	$interval		Interval of Product
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		if( !is_a( $formula, 'Math_Formula' ) )
			throw new InvalidArgumentException( 'No Formula Object given.' );
		if( !is_a( $interval, 'Math_CompactInterval' ) )
			throw new InvalidArgumentException( 'No Interval Object given.' );
		$this->formula	= $formula;
		$this->interval	= $interval;
	}
	
	/**
	 *	Calculates Product of given Formula within given compact Interval and Parameters.
	 *	@access		public
	 *	@return		mixed
	 */
	public function calculate()
	{
		$arguments	= func_get_args();
		for( $i=$this->interval->getStart(); $i<=$this->interval->getEnd(); $i++ )
		{
			$params	= array( $i );
			foreach( $arguments as $argument )
				$params[]	= $argument;
			$value	= call_user_func_array( array( &$this->formula, 'getValue' ), $params );

			if( !isset( $product ) )
				$product	= $value;
			else
				$product	*= $value;
		}
		return $product;
	}
}
?>
<?php
/**
 *	Resolution of Formula Sum within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
/**
 *	Resolution of Formula Sum  within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_FormulaSum
{
	/**	@var		Math_Formula			$formula	Formula */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval	Interval */
	protected $interval;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula within Sum
	 *	@param		Math_CompactInterval	$interval		Interval of Sum
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
	 *	Calculates Sum of given Formula within given compact Interval and Parameters.
	 *	@access		public
	 *	@return		mixed
	 */
	public function calculate()
	{
		$sum		= 0;
		$arguments	= func_get_args();
		for( $i=$this->interval->getStart(); $i<=$this->interval->getEnd(); $i++ )
		{
			$params	= array( $i );
			foreach( $arguments as $argument )
				$params[]	= $argument;
			$value	= call_user_func_array( array( &$this->formula, 'getValue' ), $params );
			$sum	+= $value;
		}
		return $sum;
	}
}
?>
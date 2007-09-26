<?php
/**
 *	Resolution of Formula Sum within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
/**
 *	Resolution of Formula Sum  within a compact Interval.
 *	@package		math
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			24.04.2006
 *	@version		0.1
 */
class FormulaSum
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$formula		Formula within Sum
	 *	@param		Interval		$interval		Interval of Sum
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		$this->_formula	= $formula;
		$this->_interval	= $interval;
	}
	
	/**
	 *	Calculates Sum of given Formula within given compact Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	function calculate()
	{
		$sum	= 0;
		$args	= func_get_args();
		for( $i=$this->_interval->getStart(); $i<=$this->_interval->getEnd(); $i++ )
		{
			$param	= array( $i );
			foreach( $args as $arg )
				$param[]	= $arg;
			$param	= implode( ", ", $param );
			$code	= "return \$this->_formula->getValue( ".$param." );";
			$sum	+= eval( $code );	
		}
		return $sum;
	}
}
?>
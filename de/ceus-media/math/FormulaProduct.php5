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
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		24.04.2006
 *	@version		0.1
 */
class FormulaProduct
{
	/**	@var		Math_Formula			$formula		Formula */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval */
	protected $interval;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula within Product
	 *	@param		Interval		i$nterval		Interval of Product
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		$this->formula	= $formula;
		$this->interval	= $interval;
	}
	
	/**
	 *	Calculates Product of given Formula within given compact Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	public function calculate()
	{
		for( $i=$this->interval->getStart(); $i<=$this->interval->getEnd(); $i++ )
		{
			if( !isset( $product ) )
				$product	= $this->formula->getValue( $i );
			else
				$product	*= $this->formula->getValue( $i );
		}
		return $product;
	}
}
?>
<?php
/**
 *	Resolution of Formula Products within a compact Interval.
 *	@package	math
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		24.04.2006
 *	@version		0.1
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
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$formula		Formula within Product
	 *	@param		Interval		i$nterval		Interval of Product
	 *	@return		void
	 */
	public function __construct( $formula, $interval )
	{
		$this->_formula	= $formula;
		$this->_interval	= $interval;
	}
	
	/**
	 *	Calculates Product of given Formula within given compact Interval.
	 *	@access		public
	 *	@return		mixed
	 */
	function calculate()
	{
		for( $i=$this->_interval->getStart(); $i<=$this->_interval->getEnd(); $i++ )
		{
			if( !isset( $product ) )
				$product	= $this->_formula->getValue( $i );
			else
				$product	*= $this->_formula->getValue( $i );
		}
		return $product;
	}
}
?>
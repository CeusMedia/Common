<?php
import( 'de.ceus-media.math.analysis.Integration' );
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *	@package		math.analysis
 *	@extends		Math_Analysis_Integration 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *	@package		math.analysis
 *	@extends		Math_Analysis_Integration 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Math_Analysis_SimpsonIntegration extends Math_Analysis_Integration
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula to integrate
	 *	@param		Math_CompactInterval	$interval		Interval to integrate within
	 *	@param		int						$nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function __construct( $formula, $interval, $nodes )
	{
		parent::__construct( $formula, $interval, $nodes );
	}
	
	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	public function integrate()
	{
		$sum		= 0;
		$factor		= 0;
		$nodes		= $this->getSamplingNodes();
		$distance	= $this->getNodeDistance();
		$sum		+= $this->formula->getValue( array_pop( $nodes ) );
		$sum		+= $this->formula->getValue( array_shift( $nodes ) );
		foreach( $nodes as $node )
		{
			$factor	= ( $factor == 4 ) ? 2 : 4;
			$sum	+= $factor * $this->formula->getValue( $node );
		}
		$sum = $sum * $distance / 3;
		return $sum;			
	}
}
?>
<?php
import( 'de.ceus-media.math.analysis.Integration' );
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Integration 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Integration 
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
class SimpsonIntegration extends Integration
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$_formula		Formula to integrate
	 *	@param		Interval		$_interval		Interval to integrate within
	 *	@param		int			$_nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function __construct ($formula, $interval, $nodes)
	{
		$this->Integration( $formula, $interval, $nodes );
	}

	/**
	 *	Returns an array of Sampling Nodes.
	 *	@access		public
	 *	@return		array
	 */
	function getSamplingNodes ()
	{
		$nodes	= array ();
		$start	= $this->_interval->getStart ();
		$end	= $this->_interval->getEnd ();
		$distance	= $this->getNodeDistance ();
		for ($i = 0; $i<$this->getNodes(); $i++)
		{
			$x = $start + $i * $distance;
			$nodes [] = $x;		
		}
		return $nodes;
	}
	
	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	function integrate ()
	{
		$sum	= 0;
		$factor	= 0;
		$nodes	= $this->getSamplingNodes ();
		$distance	= $this->getNodeDistance ();
		$sum	+= $this->_formula->getValue (array_pop($nodes));
		$sum	+= $this->_formula->getValue (array_shift ($nodes));
		foreach ($nodes as $node)
		{
			$factor = ($factor == 4) ? 2 : 4;
			$sum += $factor * $this->_formula->getValue ($node);
//			remark( "node: ".$node." | factor: ".$factor." | sum: ".$sum );
		}
		$sum = $sum * $distance / 3;
		return $sum;			
	}
}
?>
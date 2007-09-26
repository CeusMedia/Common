<?php
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *	@package		math
 *	@subpackage		analysis
 *	@extends		Object
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class Integration
{
	/**	@var	Formula		$_formula		Formula to integrate */
	var $_formula;
	/**	@var	Interval		$_interval		Interval to integrate within */
	var $_interval;
	/**	@var	Formula		$_nodes			Amount of Sampling Nodes to use */
	var $_nodes;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		Formula		$_formula		Formula to integrate
	 *	@param		Interval		$_interval		Interval to integrate within
	 *	@param		int			$_nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function __construct( $formula, $interval, $nodes )
	{
		$this->_setFormula( $formula );
		$this->_setInterval( $interval );
		$this->_setNodes( $nodes );
	}

	/**
	 *	Returns set Formula.
	 *	@access		public
	 *	@return		Formula
	 */
	function getFormula()
	{
		return $this->_formula;
	}
	
	/**
	 *	Returns set Interval.
	 *	@access		public
	 *	@return		Interval
	 */
	function getInterval()
	{
		return $this->_interval;
	}
	
	/**
	 *	Returns quantity of Sampling Nodes.
	 *	@access		public
	 *	@return		Formula
	 */
	function getNodes()
	{
		return $this->_nodes;
	}
	
	/**
	 *	Returns an array of Sampling Nodes.
	 *	@access		public
	 *	@return		array
	 */
	function getSamplingNodes()
	{
		$nodes	= array();
		$start	= $this->_interval->getStart();
		$end	= $this->_interval->getEnd();
		$distance	= $this->getNodeDistance();
		for( $i = 0; $i<$this->getNodes(); $i++ )
		{
			$x = $start + $i * $distance;
			$nodes[] = $x;		
		}
		return $nodes;
	}
	
	/**
	 *	Calculates the distance between two Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	function getNodeDistance()
	{
		$distance	= $this->_interval->getDiam() / ( $this->getNodes() - 1 );
		return $distance;
	}

	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	function integrate()
	{
		$sum	= 0;
		$nodes	= $this->getNodes()-1;
		$distance	= $this->getNodeDistance();
		$start	= $this->_interval->getStart();
		for( $i=0; $i<$nodes; $i++ )
		{
			$x		= $start + $distance * ( $i + $distance / 2 );
			$y		= $this->_formula->getValue( $x );
			$sum	+= $y;
		}
		return $distance * $sum;
	}

	//  --  PRIVATE METHODS  --  //	
	/**
	 *	Sets Formula.
	 *	@access		private
	 *	@param		Formula		$formula		Formula to integrate
	 *	@return		void
	 */
	function _setFormula( $formula )
	{
		$this->_formula	= $formula;	
	}
	
	/**
	 *	Sets Interval.
	 *	@access		private
	 *	@param		Interval		$interval		Interval to integrate within
	 *	@return		void
	 */
	function _setInterval( $interval )
	{
		$this->_interval	= $interval;	
	}
	
	/**
	 *	Sets amount of Sampling Nodes to use.
	 *	@access		private
	 *	@param		int			$nodes		Amount of Sampling Nodes to use
	 *	@return		void
	 */
	function _setNodes( $nodes )
	{
		if( $nodes < 2 )
			trigger_error( "amount of sampling points must be > 1", E_USER_ERROR );
		$this->_nodes = $nodes;
	}
}
?>
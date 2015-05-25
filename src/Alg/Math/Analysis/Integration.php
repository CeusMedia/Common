<?php
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Math.Analysis
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Calculates Integral with Sampling Nodes within a compact Interval.
 *	@category		cmClasses
 *	@package		Math.Analysis
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Math_Analysis_Integration
{
	/**	@var		Math_Formula			$formula		Formula to integrate */
	protected $formula;
	/**	@var		Math_CompactInterval	$interval		Interval to integrate within */
	protected $interval;
	/**	@var		int						$nodes			Amount of Sampling Nodes to use */
	protected $nodes;

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
		$this->setFormula( $formula );
		$this->setInterval( $interval );
		$this->setNodes( $nodes );
	}

	/**
	 *	Returns set Formula.
	 *	@access		public
	 *	@return		Math_Formula
	 */
	public function getFormula()
	{
		return $this->formula;
	}
	
	/**
	 *	Returns set Interval.
	 *	@access		public
	 *	@return		Math_CompactInterval
	 */
	public function getInterval()
	{
		return $this->interval;
	}
	
	/**
	 *	Returns quantity of Sampling Nodes.
	 *	@access		public
	 *	@return		Math_Formula
	 */
	public function getNodes()
	{
		return $this->nodes;
	}
	
	/**
	 *	Returns an array of Sampling Nodes.
	 *	@access		public
	 *	@return		array
	 */
	public function getSamplingNodes()
	{
		$nodes	= array();
		$start	= $this->interval->getStart();
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
	public function getNodeDistance()
	{
		$distance	= $this->interval->getDiameter() / ( $this->getNodes() - 1 );
		return $distance;
	}

	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	public function integrate()
	{
		$sum	= 0;
		$nodes	= $this->getNodes()-1;
		$distance	= $this->getNodeDistance();
		$start	= $this->interval->getStart();
		for( $i=0; $i<$nodes; $i++ )
		{
			$x		= $start + $distance * ( $i + $distance / 2 );
			$y		= $this->formula->getValue( $x );
			$sum	+= $y;
		}
		return $distance * $sum;
	}

	/**
	 *	Sets Formula.
	 *	@access		public
	 *	@param		Math_Formula			$formula		Formula to integrate
	 *	@return		void
	 */
	public function setFormula( $formula )
	{
		$this->formula	= $formula;	
	}
	
	/**
	 *	Sets Interval.
	 *	@access		public
	 *	@param		Math_CompactInterval	$interval		Interval to integrate within
	 *	@return		void
	 */
	public function setInterval( $interval )
	{
		$this->interval	= $interval;	
	}
	
	/**
	 *	Sets amount of Sampling Nodes to use.
	 *	@access		public
	 *	@param		int						$nodes			Amount of Sampling Nodes to use
	 *	@return		void
	 */
	public function setNodes( $nodes )
	{
		if( $nodes < 2 )
			throw new InvalidArgumentException( 'Number of Sampling Points must be greater than 1.' );
		$this->nodes = $nodes;
	}
}
?>
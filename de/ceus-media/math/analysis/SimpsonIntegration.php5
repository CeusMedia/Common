<?php
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		math.analysis
 *	@extends		Math_Analysis_Integration 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
import( 'de.ceus-media.math.analysis.Integration' );
/**
 *	Integration with Simpsons Algorithm within a compact Interval.
 *	@category		cmClasses
 *	@package		math.analysis
 *	@extends		Math_Analysis_Integration 
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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
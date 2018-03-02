<?php
/**
 *	Integration with Trapezoid Algorithm within a compact Interval.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math_Analysis_Integration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
/**
 *	Integration with Trapezoid Algorithm within a compact Interval.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math_Analysis_Integration
 *	@extends		Alg_Math_Analysis_Integration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 */
class Alg_Math_Analysis_Integration_Trapezoid extends Alg_Math_Analysis_Integration
{
	/**
	 *	Calculates integrational sum of Formula within the Interval by using Sampling Nodes.
	 *	@access		public
	 *	@return		mixed
	 */
	public function integrate()
	{
		$sum		= 0;
		$nodes		= $this->getSamplingNodes();
		$distance	= $this->getNodeDistance();
		$sum		+= $this->formula->getValue( array_pop( $nodes ) );
		$sum		+= $this->formula->getValue( array_shift ( $nodes ) );
		foreach( $nodes as $node )
			$sum += 2 * $this->formula->getValue( $node );
		$sum = $sum * $distance / 2;
		return $sum;			
	}
}
?>
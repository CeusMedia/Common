<?php
/**
 *	Neville Interpolation.
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
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
/**
 *	Neville Interpolation.
 *	@category		cmClasses
 *	@package		math.analysis
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		$Id$
 */
class Math_Analysis_NevilleInterpolation
{
	/**	@var		array		$data		Array of x and y values (Xi->Fi) */
	protected $data				= array();

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data		Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	protected function setData( $data )
	{
		$this->data	= $data;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x			Value to interpolate for
	 *	@return		double
	 */
	protected function interpolate( $x )
	{
		$t		= array();
		$keys	= array_keys( $this->data );
		$values	= array_values( $this->data );
		for( $i=0; $i<count( $keys ); $i++ )
		{
			$t[$i]	= $values[$i];
			for( $j=$i-1; $j>=0; $j-- )
				$t[$j]	= $t[$j+1] + ( $t[$j+1] - $t[$j] ) * ( $x - $keys[$i] ) / ( $keys[$i] - $keys[$j] );			
		}
		return $t[0];
	}
}
?>
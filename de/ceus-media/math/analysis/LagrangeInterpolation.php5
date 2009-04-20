<?php
/**
 *	Lagrange Interpolation.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		0.6
 */
import( 'de.ceus-media.math.Formula' );
/**
 *	Lagrange Interpolation.
 *	@package		math.analysis
 *	@uses			Math_Formula
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			03.02.2006
 *	@version		0.6
 */
class Math_Analysis_LagrangeInterpolation
{
	/**	@var		array		$data			Array of x and y values (Xi->Fi) */
	protected $data				= array();
	/**	@var		array		$expressions	Array of built Expressions */
	protected $expressions		= array();	

	/**
	 *	Sets Data.
	 *	@access		public
	 *	@param		array		$data			Array of x and y values (Xi->Fi)
	 *	@return		void
	 */
	public function setData( $data )
	{
		$this->data	= $data;
	}

	/**
	 *	Build Expressions for Interpolation.
	 *	@access		public
	 *	@return		void
	 */
	public function buildExpressions()
	{
		$this->expressions	= array();
		$values	= array_keys( $this->data );
		for( $i=0; $i<count( $values ); $i++ )
		{
			$this->expressions[$i]	= "";
			for( $k=0; $k<count( $values ) ;$k++ )
			{
				if( $k == $i )
					continue;
				$expression	= "(x-".$values[$k].")/(".$values[$i]."-".$values[$k].")";
				if( strlen( $this->expressions[$i] ) )
					$this->expressions[$i]	.= "*".$expression;
				else
					$this->expressions[$i]	= $expression;
			}
		}
	}

	/**
	 *	Returns built Expression.
	 *	@access		public
	 *	@return		array
	 */
	public function getExpressions()
	{
		return $this->expressions;
	}

	/**
	 *	Interpolates for a specific x value and returns P(x).
	 *	@access		public
	 *	@param		double		$x				Value to interpolate for
	 *	@return		double
	 *	@todo		Var x is not needed? Why getValue( 2 ) ?
	 */
	public function interpolate( $x )
	{
		$sum	= 0;
		$values	= array_values( $this->data );
		$expressions	= $this->getExpressions();
		for( $i=0; $i<count( $expressions ); $i++ )
		{
			$expression	= $expressions[$i];
			$formula	= new Math_Formula( $expression, array( "x" ) );
			$value	= $formula->getValue( 2 );
			$sum	+= $values[$i] * $value;
		}
		return $sum;
	}
}
?>
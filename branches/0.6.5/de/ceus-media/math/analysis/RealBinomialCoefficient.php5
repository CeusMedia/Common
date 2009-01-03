<?php
/**
 *	Calculation of Factorial for Reals.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			15.09.2006
 *	@version		0.6
 */
import( 'de.ceus-media.math.Factorial' );
/**
 *	Calculation of Factorial for Reals.
 *	@package		math.analysis
 *	@uses			Math_Factorial
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.04.2006
 *	@version		0.6
 */
class Math_Analysis_RealBinomialCoefficient
{
	/**
	 *	Calculates Binomial Coefficient of Top and Button Integers.
	 *	@access		public
	 *	@param		int			$top			Top Integer
	 *	@param		int			$bottom			Bottom Integer (lower than or equal to Top Integer)
	 *	@return		int
	 */
	public static function calculate( $top, $bottom )
	{
		if( $top != (int) $top )
			throw new InvalidArgumentException( 'Top Number must be an Integer.' );
		if( $bottom != (int) $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be an Integer.' );
		else
		{
			$product	= 1;
			for( $i=0; $i<$bottom; $i++ )
				$product	*= $top - $i;
			$result	= $product / Math_Factorial::calculate( $bottom );
			return $result;
		}
	}
}
?>
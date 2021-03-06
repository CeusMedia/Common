<?php
/**
 *	Calculation of Factorial for Integers.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Math_Analysis
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			24.04.2006
 */
/**
 *	Calculation of Factorial for Integers.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math_Analysis
 *	@uses			Alg_Math_Factorial
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			24.04.2006
 */
class Alg_Math_Analysis_BinomialCoefficient
{
	public function __construct(){
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
	}

	/**
	 *	Calculates Binomial Coefficient of Top and Button Integers.
	 *	@access		public
	 *	@param		int			$top			Top Integer
	 *	@param		int			$bottom			Bottom Integer (lower than or equal to Top Integer)
	 *	@return		int
	 */
	public function calculate( $top, $bottom )
	{
		if( $top < $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be lower than or equal to Top Number.' );
		else if( $top != (int) $top )
			throw new InvalidArgumentException( 'Top Number must be an Integer.' );
		else if( $bottom != (int) $bottom )
			throw new InvalidArgumentException( 'Bottom Number must be an Integer.' );
		else
		{
			$result	= Alg_Math_Factorial::calculate( $top ) / ( Alg_Math_Factorial::calculate( $bottom ) * Alg_Math_Factorial::calculate( $top - $bottom ) );
			return $result;
		}
	}
}

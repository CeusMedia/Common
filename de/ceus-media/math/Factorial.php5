<?php
/**
 *	Calculation of Factorial for Integers.
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
 *	@package		math
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.04.2006
 *	@version		$Id$
 */
/**
 *	Calculation of Factorial for Integers.
 *	@category		cmClasses
 *	@package		math
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			24.04.2006
 *	@version		$Id$
 */
class Math_Factorial
{
	/**
	 *	Calculates Factorial of Integer recursive and returns Integer or Double.
	 *	@access		public
	 *	@static
	 *	@param		int			$integer		Integer (<=170) to calculate Factorial for
	 *	@return		mixed
	 */
	public static function calculate( $integer )
	{
		if( $integer < 0 )
			throw new InvalidArgumentException( "Factorial is defined for positive natural Numbers only" );
		else if( !is_int( $integer ) )
			throw new InvalidArgumentException( "Factorial is defined for natural Numbers (Integer) only" );
		else if( $integer == 0 )
			return 1;
		else
			return $integer * self::calculate( $integer - 1 );
		return 0;
	}
}
?>
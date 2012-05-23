<?php
/**
 *	Scalar Product of two Vectors.
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
 *	@package		Math.Algebra.Vector
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Scalar Product of two Vectors.
 *	@category		cmClasses
 *	@package		Math.Algebra.Vector
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Math_Algebra_Vector_ScalarProduct
{
	/**
	 *	Returns Scalar Product of two Vectors
	 *	@access		public
	 *	@param		Math_Algebra_Vector		$vector1		Vector 1
	 *	@param		Math_Algebra_Vector		$vector2		Vector 2
	 *	@return		mixed
	 */
	public function produce( $vector1, $vector2 )
	{
		$sum = 0;
		if( $vector1->getDimension() != $vector2->getDimension() )
			throw new Exception( 'Dimensions of Vectors are not compatible.' );

		for( $i=0; $i<$vector1->getDimension(); $i++)
			$sum += $vector1->getValueFromIndex( $i ) * $vector2->getValueFromIndex( $i );
		return $sum;
	}
}
?>
<?php
/**
 *	Calculates artithmetic and geometric Average.
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
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.09.2006
 *	@version		$Id$
 */
/**
 *	Calculates artithmetic and geometric Average.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			15.09.2006
 *	@version		$Id$
 *	@todo			finish Implementation
 */
class Alg_Math_Average
{
	/**
	 *	Calculates artithmetic Average.
	 *	@access		public
	 *	@static
	 *	@param		array		$values			Array of Values.
	 *	@param		int			$accuracy		Accuracy of Result
	 *	@return		float
	 */
	public static function arithmetic( $values, $accuracy = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		$sum	= 0;
		foreach( $values as $value )
			$sum	+= $value;
		$result	= $sum / count( $values );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}

	/**
	 *	Calculates geometric Average.
	 *	@access		public
	 *	@static
	 *	@param		array		$values			Array of Values
	 *	@param		int			$accuracy		Accuracy of Result
	 *	@return		float
	 */
	public static function geometric( $values, $accuracy = NULL )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		$product	= 1;
		foreach( $values as $value )
			$product	*= $value;
		$result	= pow( $product, 1 / count( $values ) );
		if( $accuracy >= 0 )
			$result	= round( $result, $accuracy );
		return $result;
	}
}
?>

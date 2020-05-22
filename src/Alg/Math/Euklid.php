<?php
/**
 *	Algorithmus von Euklid.
 *
 *	Bestimmen des groessten gemeinsamen Teilers ggT
 *	und des kleinsten gemeinsamen Vielfachen kgV
 *	zweier natuerlicher Zahlen m und n
 *	mittels euklidischen Algorithmus.
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
 *	@version		$Id$
 */
/**
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class Alg_Math_Euklid
{
	/**
	 *	ggT( m, n)
	 *	@access		public
	 *	@static
	 *	@param		int			$m			natuerliche Zahlen > 0
	 *	@param		int			$n			natuerliche Zahlen > 0
	 *	@return		int
	 */
	public static function ggT( $m, $n )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		if( $n != 0 )
			return self::ggT( $n, $m % $n );
		else
			return $m;
	}

	public static function ggTe( $a, $b )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		$array	= self::ggTe_rec( $a, $b );
		return $array[0];
	}

	public static function ggTe_rec( $a, $b )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		if( $b == 0 )
			$array	= array( $a, 1, 0 );
		else
		{
			$tmp	= self::ggTe_rec( $b, $a % $b );
			$array	= array( $tmp[0], $tmp[2], $tmp[1] - round( $a / $b ) * $tmp[2] );
		}
		return $array;
	}

	/**
	 *	kgV( m, n)
	 *	@access		public
	 *	@static
	 *	@param		int			$m			natuerliche Zahlen > 0
	 *	@param		int			$n			natuerliche Zahlen > 0
	 *	@return		int
	 */
	public static function kgV( $m, $n )
	{
		return $m * $n / self::ggT( $m, $n );
	}
}
?>

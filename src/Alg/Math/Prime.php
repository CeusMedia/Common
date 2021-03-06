<?php
/**
 *	Prime Numbers
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
 *	@since			30.4.2005
 */
/**
 *	Prime Numbers
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Math
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			30.4.2005
 */
class Alg_Math_Prime
{

	/**
	 *	Returns a List of Prime Factors if given Number is dividable with Prime Numbers.
	 *	@access		public
	 *	@static
	 *	@param		int			$number
	 *	@param		array		$list
	 *	@return		array
	 */
	public static function getPrimeFactors( $number, $list = array() )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		$edge	= floor( sqrt( $number ) );
		$primes	= self::getPrimes( $edge );
		if( self::isPrime( $number ) )
		{
			$list[] = $number;
		}
		else if( count( $primes ) )
		{
			rsort( $primes );
			foreach( $primes as $prime )
			{
				if( $number % $prime == 0 )
				{
					$tmp = $list;
					$tmp[] = $prime;
					$rest = $number / $prime;
					$result = self::getPrimeFactors( $rest, $tmp );
					if( count( $result ) )
					{
						sort( $result );
						return $result;
					}
				}
			}
		}
		return $list;
	}

	/**
	 *	Returns all Primes from 2 to a given Number
	 *	@access		public
	 *	@static
	 *	@param		int			$max	Greatest Number to get Primes for
	 *	@return		array
	 */
	public static function getPrimes( $max )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		$primes = $numbers = array();
		for( $i=2; $i<=$max; $i++ )
			$numbers[$i] = true;

		$edge = floor( sqrt( $max ) );
		for( $i=2; $i<=$edge; $i++ )
			if( $numbers[$i] )
				foreach( $numbers as $key => $prime )
					if( $key > $i )
						if( $prime )
							if( $key % $i == 0 )
								$numbers[$key] = false;

		foreach( $numbers as $key => $prime )
			if( $prime )
				$primes[] = $key;
		return $primes;
	}

	/**
	 *	Indicates whether a given Number is a Prime Number.
	 *	@access		public
	 *	@static
	 *	@param		int			$number		Number to be checked
	 *	@return		bool
	 */
	public static function isPrime( $number )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.5' )
			->setExceptionVersion( '0.9' )
			->message( sprintf(
				'Please use %s (%s) instead',
				'public library "CeusMedia/Math"',
			 	'https://packagist.org/packages/ceus-media/math'
			) );
		if( $number < 2 )
			return false;
		$edge = floor( sqrt( $number ) );
		for( $i=2; $i<=$edge; $i++ )
			if( $number % $i == 0 )
				return false;
		return true;
	}
}

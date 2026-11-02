<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Search;

/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Interpolation
{
	/**
	 *	Calculates next index by interpolation between bounds.
	 *	@access		protected
	 *	@param		array		$list			Sorted list of numbers
	 *	@param		mixed		$search			Number to search
	 *	@param		int			$lowerBound		Lower bound
	 *	@param		int			$upperBound		Upper bound
	 *	@return		int
	 */
	protected function calculateIndex( array $list, mixed $search, int $lowerBound, int $upperBound ): int
	{
		$spanValues	= $list[$upperBound] - $list[$lowerBound];
		if( 0 == $spanValues )
			return $lowerBound;
		$spanIndex	= $upperBound - $lowerBound;
		$spanDiff	= $search - $list[$lowerBound];
		return $lowerBound + (int) floor( $spanIndex * ( $spanDiff / $spanValues ) );
	}

	/**
	 *	Searches in a sorted list of numbers and returns index of found value, or -1.
	 *	@access		public
	 *	@param		array		$list			Sorted list of numbers
	 *	@param		mixed		$search			Number to search
	 *	@return		int
	 */
	public function search( array $list, mixed $search ): int
	{
		$list		= array_values( $list );
		$lowerBound	= 0;
		$upperBound	= count( $list ) - 1;
		while( $lowerBound <= $upperBound && $search >= $list[$lowerBound] && $search <= $list[$upperBound] ){
			$index	= $this->calculateIndex( $list, $search, $lowerBound, $upperBound );
			if( $list[$index] == $search )
				return $index;
			if( $list[$index] < $search )
				$lowerBound	= $index + 1;
			else
				$upperBound	= $index - 1;
		}
		return -1;
	}
}

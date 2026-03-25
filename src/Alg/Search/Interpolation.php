<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Search;

/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Interpolation
{
	/**
	 *	Calculates next bound index.
	 *	@access		protected
	 *	@param		array		$list			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@param		int			$lowerBound		Last lower bound
	 *	@param		int			$upperBound		Last higher bound
	 *	@return 	int
	 */
	protected function calculateIndex(array $list, $search, int $lowerBound, int $upperBound ): int
	{
		$spanIndex	= $list[$upperBound] - $list[$lowerBound];
		$spanValues	= $upperBound - $lowerBound;
		$spanDiff	= $search - $list[$lowerBound];
		return $lowerBound + (int) round( $spanValues * ( $spanDiff / $spanIndex ) );
	}

	/**
	 *	Searches in List and returns position if found, else -1.
	 *	@access		public
	 *	@param		array		$list			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@return 	int
	 */
	public function search( array $list, $search ): int
	{
		$lowerBound	= 0;
		$upperBound	= sizeof( $list ) - 1;
		do{
			$index = $this->calculateIndex( $list, $search, $lowerBound, $upperBound );
//			echo "[".$lowerBound."|".$upperBound."]  search_index: ".$index.": ".$list[$index]."<br>";
			if( $index < $lowerBound || $index > $upperBound )
				return -1;
			if( $list[$index] == $search )
				return $index;
			if( $list[$index] < $search )
				$lowerBound	= $index+1;
			else
				$upperBound	= $index-1;
		} while( $lowerBound < $upperBound );
		return -1;
	}
}

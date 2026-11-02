<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Strange Search Algorithm.
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
 *	Strange Search Algorithm.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Strange
{
	/**	@var		int			$counter		internal counter of steps */
	protected int $counter;

	/**
	 *	Searches in a sorted list by dividing the search range into thirds (ternary search).
	 *	Returns index of found value, or -1.
	 *	@access		public
	 *	@param		array		$array		Sorted list
	 *	@param		mixed		$key		Value to search
	 *	@param		int			$left		Left bound (default: start of list)
	 *	@param		int			$right		Right bound (default: end of list)
	 *	@return		int
	 */
	public function search( array $array, mixed $key, int $left = 0, int $right = 0 ): int
	{
		$array			= array_values( $array );
		$this->counter	= 0;
		if( 0 === $right )
			$right	= count( $array ) - 1;
		while( $left <= $right ){
			$this->counter++;
			$index1	= $left + intdiv( $right - $left, 3 );
			$index2	= $right - intdiv( $right - $left, 3 );
			if( $key == $array[$index1] )
				return $index1;
			if( $key == $array[$index2] )
				return $index2;
			if( $key < $array[$index1] )
				$right	= $index1 - 1;
			else if( $key > $array[$index2] )
				$left	= $index2 + 1;
			else{
				$left	= $index1 + 1;
				$right	= $index2 - 1;
			}
		}
		return -1;
	}
}

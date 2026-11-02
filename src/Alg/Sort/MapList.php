<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Sorts a List of Maps (=associative Arrays) by one Column or many Columns.
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
 *	@package		CeusMedia_Common_Alg_Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Sort;

/**
 *	Sorts a List of Maps (=associative Arrays) by one Column or many Columns.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MapList
{
	public const DIRECTION_ASC		= 0;
	public const DIRECTION_DESC		= 1;

	/**
	 *	Sorts a List of associative Arrays by a Column and Direction.
	 *	@access		public
	 *	@static
	 *	@param		array		$data		List of associative Arrays
	 *	@param		string		$key		Column to sort by
	 *	@param		int			$direction	Sort Direction (0 - ::DIRECTION_ASC | 1 - ::DIRECTION_DESC)
	 *	@return		array
	 */
	public static function sort( array $data, string $key, int $direction = self::DIRECTION_ASC ): array
	{
		return self::sortByMultipleColumns( $data, [$key => $direction] );
	}

	/**
	 *	Sorts a List of associative Arrays by several Columns and Directions.
	 *	@access		public
	 *	@static
	 *	@param		array		$data		List of associative Arrays
	 *	@param		array		$orders		Map of Columns and their Directions (0 - ::DIRECTION_ASC | 1 - ::DIRECTION_DESC)
	 *	@return		array
	 */
	public static function sortByMultipleColumns( array $data, array $orders ): array
	{
		if( [] === $orders )
			return array_values( $data );
		//  usort is stable, so entries with equal values keep their order
		usort( $data, static function( array $a, array $b ) use ( $orders ): int {
			foreach( $orders as $key => $direction ){
				$result	= ( $a[$key] ?? NULL ) <=> ( $b[$key] ?? NULL );
				if( 0 !== $result )
					return $direction == self::DIRECTION_DESC ? -$result : $result;
			}
			return 0;
		} );
		return $data;
	}
}

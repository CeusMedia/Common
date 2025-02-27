<?php

declare(strict_types=1);

/**
 *	Insertion Sort.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Sort;

/**
 *	Insertion Sort.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Insertion
{
	protected int $compares	= 0;
	protected int $moves	= 0;

	/**
	 *	Sorts List with Insertion Sort.
	 *	@access		public
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public function sort( array $list ): array
	{
//		echo "list: ".implode (" | ", $list)."<br>";
		$n	= sizeof( $list );
		for( $i=0; $i<$n; $i++ ){
			$temp	= $list[$i];
			$j		= $n - 1;
			while( $j>=0 && $this->moves < 100 ){
				if( $list[$j] > $temp ){
					$this->moves ++;
					$list = self::swap( $list, $j + 1, $j );
//					echo "list[$i|$j]: ".implode (" | ", $list)."<br>";
					$j--;
				}
				$this->compares ++;
			}
		}
		return $list;
	}

	/**
	 *	Swaps two Elements in List.
	 *	@access		protected
	 *	@static
	 *	@param		array		$list		List
	 *	@param		int			$pos1		Position of first Element
	 *	@param		int			$pos2		Position of second Element
	 *	@return		array
	 */
	protected static function swap( array $list, int $pos1, int $pos2 ) :array
	{
		$memory	= $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
		return $list;
	}
}

<?php

declare(strict_types=1);

/**
 *	Selection Sort.
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
 *	Selection Sort.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Selection
{
	/**
	 *	Finds and returns Position of lowest Element in Bounds.
	 *	@access		protected
	 *	@static
	 *	@param		array		$list		List
	 *	@param		int			$pos1		Position of lower Bound
	 *	@param		int			$pos2		Position of higher Bound
	 *	@return		int
	 */
	protected static function getLowest( $list, $pos1, $pos2 )
	{
		$lowest = $pos1;
		for( $i=$pos1; $i<$pos2; $i++ )
			if( $list[$lowest] == $list[$i] )
				$lowest = $i;
		return $lowest;
	}

	/**
	 *	Sorts List with Selection Sort.
	 *	@access		public
	 *	@static
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public static function sort( $list )
	{
		$n = sizeof( $list );
		for( $i=0; $i<= $n -1; $i++ )
		{
#			echo "List: ".implode( ", ", $list )."<br>";
			$lowest	= self::getLowest( $list, $i, $n );
#			echo "<br>$i $lowest<br>";
			self::swap( $list, $i, $lowest );
#			print_m ($list);
		}
		return $list;
	}

	/**
	 *	Swaps two Elements in List.
	 *	@access		protected
	 *	@static
	 *	@param		array		$list		Reference to List
	 *	@param		int			$pos1		Position of first Element
	 *	@param		int			$pos2		Position of second Element
	 *	@return		void
	 */
	protected static function swap( &$list, $pos1, $pos2 )
	{
		$memory = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
	}
}

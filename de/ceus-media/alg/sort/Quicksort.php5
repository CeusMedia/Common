<?php
/**
 *	Sorting numeric arrays with the Quicksort algorithm.
 *
 *	Copyright (c) 2005  Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *
 *	This library is free software; you can redistribute it and/or
 *	modify it under the terms of the GNU Lesser General Public
 *	License as published by the Free Software Foundation; either
 *	version 2.1 of the License, or (at your option) any later version.
 *
 *	This library is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *	Lesser General Public License for more details.
 *
 *	You should have received a copy of the GNU Lesser General Public
 *	License along with this library; if not, write to the Free Software
 *	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA
 *
 *	@license		LGPL
 *	@copyright		(c) 2005 by Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Sorting numeric arrays with the Quicksort algorithm.
 *	@package		alg.sort
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Alg_Sort_Quicksort
{

	/**
	 *	Sorts an array of numeric values with the quicksort algorithm.
	 *	@param		array		$array		Array of numeric values passed by reference
	 *	@param		int			$first		Start index
	 *	@param		int			$last		End index
	 *	@return		bool
	 */
	public static function sort( &$array, $first = NULL, $last = NULL )
	{
		if( !is_array( $array ) )
			return FALSE;
		if( is_null( $first ) )
			$first = 0;
		if( is_null( $last ) )
			$last = count( $array ) - 1;
		if( $first < $last )
		{
			$middle		= $first + $last;
			$middle		= floor( ( $first + $last ) / 2 );
			$compare	= $array[$middle];
			$left		= $first;
			$right	= $last;
			while( $left <= $right )
			{
				while( $array[$left] < $compare )
					$left++;
				while( $array[$right] > $compare )
					$right--;
				if( $left <= $right )
				{
					self::swap( $array, $left, $right );
					$left++;
					$right--;
				}
			}
			self::sort( $array, $first, $right );
			self::sort( $array, $left, $last );
		}
		return $array;
	}

	/**
	 *	Swaps two values.
	 *	@access		protected
	 *	@param		array   	$array		Array of numeric values passed by reference
	 *	@param		int			$pos1		First index
	 *	@param		int			$pos2 		Second index
	 *	@return		void
	 */
	protected static function swap( &$array, $pos1, $pos2 )
	{
		if( isset( $array[$pos1] ) && isset( $array[$pos2] ) )
		{
			$memory	= $array[$pos1];
			$array[$pos1] = $array[$pos2];
			$array[$pos2] = $memory;
		}
	}
}
?>
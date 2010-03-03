<?php
/**
 *	Sorting numeric arrays with the Quicksort algorithm.
 *
 *	Copyright (c) 2008 Christian W�rker (ceus-media.de)
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
 *	@license		LGPL
 *	@copyright		(c) 2005 by Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@package		alg.sort
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
/**
 *	Sorting numeric arrays with the Quicksort algorithm.
 *	@package		alg.sort
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian W�rker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
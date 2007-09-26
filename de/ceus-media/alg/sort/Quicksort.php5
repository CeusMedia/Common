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
 *	@copyright	(c) 2005 by Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@package	alg
 *	@subpackage	sort
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	@todo		Code Documentation
 */
class Quicksort
{

	/**
	 *	Sorts an array of numeric values with the quicksort algorithm.
	 *	@param		array	array	Array of numeric values passed by reference
	 *	@param		int		first		Start index
	 *	@param		int		last		End index
	 *	@return		bool
	 */
	function sort (&$array, $first = null, $last = null)
	{
		if (!is_array($array))
			return false;
		if (is_null($first))
			$first = 0;
		if (is_null($last))
			$last = count($array) - 1;
		if ($first < $last)
		{
			$middle	= $first + $last;
			$middle	= floor(($first + $last) / 2);
			$compare = $array[$middle];
			$left		= $first;
			$right	= $last;
			while ($left <= $right)
			{
				while ($array[$left] < $compare)
					$left++;
				while ($array[$right] > $compare)
					$right--;
				if ($left <= $right)
				{
					Quicksort::_swap($array, $left, $right);
					$left++;
					$right--;
				}
			}
			Quicksort::sort($array, $first, $right);
			Quicksort::sort($array, $left, $last);
		}
		return $array;
	}

	/**
	 *	Swaps two values.
	 *	@access		public
	 *	@param		array   		Array of numeric values passed by reference
	 *	@param		integer 		First index
	 *	@param		integer 		Second index
	 *	@return		void
	 */
	function _swap(&$array, $a, $b)
	{
		if (isset($array[$a]) && isset($array[$b]))
		{
			$mem = $array[$a];
			$array[$a] = $array[$b];
			$array[$b] = $mem;
		}
	}
}
?>
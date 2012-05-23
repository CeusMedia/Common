<?php
/**
 *	Selection Sort.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@category		cmClasses
 *	@package		Alg.Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Selection Sort.
 *	@category		cmClasses
 *	@package		Alg.Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Alg_Sort_Selection
{
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
	 *	Finds and returns Position of lowest Element in Bounds.
	 *	@access		protected
	 *	@static
	 *	@param		array		$list		List
	 *	@param		int			$pos1		Position of lower Bound
	 *	@param		int			$pos1		Position of higher Bound
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
	 *	Swaps two Elements in List.
	 *	@access		protected
	 *	@static
	 *	@param		array		$list		Reference to List
	 *	@param		int			$pos1		Position of first Element
	 *	@param		int			$pos1		Position of second Element
	 *	@return		void
	 */
	protected static function swap( &$list, $pos1, $pos2 )
	{
		$memory = $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
	}
}
?>
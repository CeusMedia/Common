<?php
/**
 *	Insertion Sort.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		alg.sort
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
/**
 *	Insertion Sort.
 *	@category		cmClasses
 *	@package		alg.sort
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
class Alg_Sort_InsertionSort
{
	protected $compares	= 0;
	protected $moves	= 0;
	
	/**
	 *	Sorts List with Insertion Sort.
	 *	@access		public
	 *	@param		array		$list		List to sort
	 *	@return		array
	 */
	public function sort( $list )
	{
//		echo "list: ".implode (" | ", $list)."<br>";
		$n	= sizeof( $list );
		for( $i=0; $i<$n; $i++ )
		{
			$temp	= $list[$i];
			$j		= $n - 1;
			while( $j>=0 && $this->moves < 100 )
			{
				if( $list[$j] > $temp )
				{
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
	 *	@param		int			$pos1		Position of second Element
	 *	@return		array
	 */
	protected static function swap( $list, $pos1, $pos2 )
	{
		$memory	= $list[$pos1];
		$list[$pos1] = $list[$pos2];
		$list[$pos2] = $memory;
		return $list;
	}
}
?>
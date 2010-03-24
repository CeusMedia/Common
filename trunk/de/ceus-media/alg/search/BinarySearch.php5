<?php
/**
 *	Binary Search Algorithm.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		alg.search
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Binary Search Algorithm.
 *	@category		cmClasses
 *	@package		alg.search
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Alg_Search_BinarySearch
{
	/**	@var		int		$counter	internal counter of steps */
	protected $counter;
	
	/**
	 *	Searches in List and returns position if found, else 0.
	 *	@access		public
	 *	@param		array		$list		List to search in
	 *	@param		mixed		$search		Element to search
	 *	@param		int			$pos		Position (initial = 0)
	 *	@return 	int
	 */
	public function search( $list, $search, $pos = 0 )
	{
		$size = sizeof( $list );
		if( $size == 1 )
		{
			if( $list[0] == $search )
				return $list[0];
			else
				return -1;
		}
		else
		{
			$this->counter++;
			$mid = floor( $size / 2 );
			if( $search < $list[$mid] )
			{
				$list = array_slice( $list, 0, $mid );
				return $this->search( $list, $search, $pos );
			}
			else
			{
				$list = array_slice( $list, $mid );
				return $this->search( $list, $search, $pos );
			}
		}
	}
}
?>
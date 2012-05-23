<?php
/**
 *	Gnome Sort.
 *
 *	Copyright (c) 2010 Christian Würker (ceusmedia.com)
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
 *	Gnome Sort.
 *	@category		cmClasses
 *	@package		Alg.Sort
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@link			http://www.cs.vu.nl/~dick/gnomesort.html
 *	@version		$Id$
 */
class Alg_Sort_Gnome
{
	/**
	 *	Sorts List in-place with Gnome Sort.
	 *	@access		public
	 *	@static
	 *	@param		array		$list		Reference of list to sort
	 */
	public static function sort( &$list )
	{
		$n	= count( $list );
		$i	= 0;
		while( $i < $n )
		{
			if( $i == 0 || $list[$i-1] <= $list[$i] )
				$i++;
			else
			{
				$tmp = $list[$i];
				$list[$i]	= $list[$i-1];
				$list[--$i] = $tmp;
			}
		}
	}
}
?>
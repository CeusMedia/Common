<?php
/**
 *	Strange Search Algorithm.
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
 *	@package		alg.search
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
/**
 *	Strange Search Algorithm.
 *	@category		cmClasses
 *	@package		alg.search
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.5
 */
class Alg_Search_StrangeSearch
{
	/**	@var		int			$counter		internal counter of steps */
	protected $counter;
	
	/**
	 *	Searches in List and returns position if found.
	 *	@access		public
	 *	@param		array		$ist			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@param		int			$left			Left bound
	 *	@param		int			$right			Right bound
	 *	@return 	int
	 */
	public function search( $array, $key, $left = FALSE, $right = FALSE )
	{
		if( !$right )
		{
			$left	= 0;
			$right	= sizeof( $array ) - 1;
			$this->counter = 0;
		}
		$this->counter++;
		$index1	= round( $left + ( $right - $left ) / 3, 0 );
		$index2	= round( $left + ( ( $right-$left ) / 3 ) * 2, 0 );
		//echo "searching from $left to $right [$index1 - $index2]<br>";
		if( $key == $array[$index1] )
			return ":".$index1;
		if( $key == $array[$index2] )
			return ":".$index2;
		if( $left == $right )
			return false;
		if( $key < $array[$index1] )
			return $this->search( $array, $key, $left, $index1 );
		else if( $key >= $array[$index2] )
			return $this->search( $array, $key, $index2, $right );
		else
			return $this->search( $array, $key, $index1 + 1, $index2 - 1 );
	}
}
?>
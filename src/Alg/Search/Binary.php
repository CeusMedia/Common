<?php

declare(strict_types=1);

/**
 *	Binary Search Algorithm.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Alg\Search;

/**
 *	Binary Search Algorithm.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Binary
{
	/**	@var		int		$counter	internal counter of steps */
	protected $counter;

	/**
	 *	Searches in sorted List and returns position if found, else -1.
	 *	@access		public
	 *	@param		array		$list		Sorted list to search in
	 *	@param		mixed		$search		Element to search
	 *	@param		int			$pos		Position offset of list (initial = 0)
	 *	@return 	int			Position of element or -1 if not found
	 */
	public function search( array $list, mixed $search, int $pos = 0 ): int
	{
		$list	= array_values( $list );
		$size	= count( $list );
		if( 0 === $size )
			return -1;
		if( 1 === $size )
			return $list[0] == $search ? $pos : -1;
		$this->counter++;
		$mid	= intdiv( $size, 2 );
		if( $search < $list[$mid] )
			return $this->search( array_slice( $list, 0, $mid ), $search, $pos );
		return $this->search( array_slice( $list, $mid ), $search, $pos + $mid );
	}
}

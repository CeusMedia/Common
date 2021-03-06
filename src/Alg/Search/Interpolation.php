<?php
/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Implementation of interpolation search algorithm for sorted lists of numbers.
 *	@category		Library
 *	@package		CeusMedia_Common_Alg_Search
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Alg_Search_Interpolation{

	/**
	 *	Calculates next bound index.
	 *	@access		protected
	 *	@param		array		$ist			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@param		int			$lowbound		Last lower bound
	 *	@param		int			$highbound		Last higher bound
	 *	@return 	int
	 */
	protected function calculateIndex( $list, $search, $lowbound, $highbound )
	{
		$spanIndex	= $list[$highbound] - $list[$lowbound];
		$spanValues	= $highbound - $lowbound;
		$spanDiff	= $search - $list[$lowbound];
		$index		= $lowbound + round( $spanValues * ( $spanDiff / $spanIndex ) );
		return $index;
	}
	/**
	 *	Searches in List and returns position if found, else -1.
	 *	@access		public
	 *	@param		array		$ist			List to search in
	 *	@param		mixed		$search			Element to search
	 *	@return 	int
	 */
	public function search( $list, $search )
	{
		// lowbound - untergrenze
		$lowbound	= 0;
		// highbound - obergrenze
		$highbound	= sizeof( $list ) - 1;
		do
		{
			$index = $this->calculateIndex( $list, $search, $lowbound, $highbound );
//			echo "[".$lowbound."|".$highbound."]  search_index: ".$index.": ".$list[$index]."<br>";
			if( $index < $lowbound || $index > $highbound )
				return -1;
			if( $list[$index] == $search )
				return $index;
			if( $list[$index] < $search )
				$lowbound	= $index+1;
			else
				$highbound	= $index-1;
		}
		while( $lowbound < $highbound );
		return -1;
	}
}

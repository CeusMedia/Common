<?php
/**
 *	Sorts a List of Maps (=associative Arrays) by one Column or many Columns.
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
 *	@package		alg.sort
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Sorts a List of Maps (=associative Arrays) by one Column or many Columns.
 *	@category		cmClasses
 *	@package		alg.sort
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
class Alg_Sort_MapList
{
	const DIRECTION_ASC		= 0;
	const DIRECTION_DESC	= 1;

	/**
	 *	Sorts a List of associative Arrays by a Column and Direction.
	 *	@access		public
	 *	@static
	 *	@param		array		$data		List of associative Arrays
	 *	@param		string		$key		Column to sort by
	 *	@param		int			$direction	Sort Direction (0 - ::DIRECTION_ASC | 1 - ::DIRECTION_DESC)
	 *	@return		array
	 */
	public static function sort( $data, $key, $direction = self::DIRECTION_ASC )
	{
		return self::sortByMultipleColumns( $data, array( $key => $direction ) );
	}
	
	/**
	 *	Sorts a List of associative Arrays by several Columns and Directions.
	 *	@access		public
	 *	@static
	 *	@param		array		$data		List of associative Arrays
	 *	@param		string		$order		Map of Columns and their Directions (0 - ::DIRECTION_ASC | 1 - ::DIRECTION_DESC)
	 *	@return		array
	 */
	public static function sortByMultipleColumns( $data, $orders )
	{
		$key		= array_pop( array_keys( $orders ) );						//  get first Column
		$direction	= $orders[$key];											//  get first Diection
		$orders		= array_slice( $orders, 1 );								//  remove Order from Order Map
		$list		= array();													//  prepare Index List
		foreach( $data as $entry )												//  iterate Data Array
			$list[$entry[$key]][]	= $entry;									//  index by Column Key

		if( $direction == self::DIRECTION_ASC )									//  ascending
			ksort( $list );														//  sort Index List
		else																	//  descending
			krsort( $list );													//  reverse sort Index List

		$array	= array();														//  prepare new Data Array
		foreach( $list as $entries )											//  iterate Index List
		{
			if( $orders && count( $entries ) > 1 )
				$entries	= self::sortByMultipleColumns( $entries, $orders );
			foreach( $entries as $entry)										//  ...
				$array[]	= $entry;											//  fill new Data Array
		}
		return $array;															//  return new Data Array
	}
}
?>
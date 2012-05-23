<?php
/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
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
 *	@package		ADT.List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			07.05.2008
 *	@version		$Id$
 */
/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
 *	@category		cmClasses
 *	@package		ADT.List
 *	@extends		ADT_List_Dictionary
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			07.05.2008
 *	@version		$Id$
 *	@todo			Unit Test
 */
class ADT_List_LevelMap extends ADT_List_Dictionary
{
	/**
	 *	Return a Value or Pair Map of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			return $this->pairs[$key];															//  return Value
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			$list		= array();																//  define empty Map
			$length		= strlen( $key );														//  get Length of Prefix Key outside the Loop
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					$list[substr( $pairKey, $length )]	= $pairValue;							//  collect Pair
			}
			if( count( $list ) )																//  found Pairs
				return $list;																	//  return Pair Map
		}
		return NULL;																			//  nothing found
	}
	
	/**
	 *	Indicates whether a Key or Key Prefix is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 */
	public function has( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			return TRUE;
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					return TRUE;
			}
		}
		return FALSE;
	}
	
	/**
	 *	Removes a Value or Pair Map from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		void
	 */
	public function remove( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			unset( $this->pairs[$key] );														//  remove Pair
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
					continue;																	//  skip Pair
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					unset( $this->pairs[$pairKey] );											//  remove Pair
			}
		}
	}
	
	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		string		$value		Value of Key
	 *	@param		bool		$sort		Flag: sort by Keys after Insertion	
	 *	@return		void
	 */
	public function set( $key, $value, $sort = TRUE )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( is_array( $value ) )																//  Pair Map given
			foreach( $value as $pairKey => $pairValue )											//  iterate given Pair Map
				$this->pairs[$key.".".$pairKey]	= $pairValue;									//  add Pair to stores Pairs
		else																					//  single Value given
			$this->pairs[$key]	= $value;														//  set Pair
		if( $sort )																				//  sort after Insertion is active
			ksort( $this->pairs );																//  sort stored Pairs by Keys
	}
}
?>
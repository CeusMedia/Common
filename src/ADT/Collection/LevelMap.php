<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_ADT_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Collection;

use CeusMedia\Common\Exception\Data\Missing as MissingDataException;
use InvalidArgumentException;

/**
 *	A Map with Level Support.
 *	It is a Dictionary where Keys can contain Dots.
 *	All Method work with complete Keys and single Values or Prefix Keys and Arrays.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Unit Test
 */
class LevelMap extends Dictionary
{
	/** @var	non-empty-string	$divider	Level dividing sign */
	protected string $divider		= ".";

	/**
	 *	Constructor.
	 *	@param		array		$array
	 *	@param		string		$divider		Level dividing sign
	 */
	public function __construct( array $array = [], string $divider = '.' )
	{
		parent::__construct( $array );
		if( 0 === strlen( $divider ) )
			throw new MissingDataException( 'Divider cannot be empty' );
		$this->divider	= $divider;
	}

	/**
	 *	Return a Value or Pair Map of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		mixed		$default	Value to return if key is not set, default: NULL
	 *	@return		mixed
	 *	@throws		InvalidArgumentException	if key is invalid
	 */
	public function get( string $key, mixed $default = NULL ): mixed
	{
		//  no Key given
		if( 0 === strlen( trim( $key ) ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Key is set on its own
		if( isset( $this->pairs[$key] ) )
			//  return Value
			return $this->pairs[$key];
		//  Key has not been found

		//  prepare Prefix Key to search for
		$key		.= $this->divider;
		//  define empty Map
		$list		= [];
		//  get Length of Prefix Key outside the Loop
		$length		= strlen( $key );
		//  iterate all stores Pairs
		foreach( $this->pairs as $pairKey => $pairValue ){
			//  Prefix Key is found
			if( str_starts_with( $pairKey, $key ) )
				//  collect Pair
				$list[substr( $pairKey, $length )]	= $pairValue;
		}
		//  found Pairs
		if( count( $list ) )
			//  return Pair Map
			return $list;
		//  nothing given default, default: NULL
		return $default;
	}

	/**
	 *	@todo	test + rename + code doc + inline doc
	 */
	public function getKeySections( string $prefix = NULL ): array
	{
		/** @var array $pairs */
		$pairs		= $this->getAll( $prefix );
		$keys		= array_keys( $pairs );
		natcasesort( $keys );
		$sections		= [];
		$lastSection	= NULL;
		foreach( $keys as $key ){
			if( !substr_count( $key, $this->divider ) )
				continue;
			$parts		= explode( $this->divider, $key );
			$section	= array_shift( $parts );
			if( $section !== $lastSection ){
				$lastSection	= $section;
				$sections[]		= $section;
			}
		}
		return $sections;
	}

	/**
	 *	Indicates whether a Key or Key Prefix is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 *	@throws		InvalidArgumentException	if key is invalid
	 */
	public function has( string $key ): bool
	{
		//  no Key given
		if( 0 === strlen( trim( $key ) ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Key is set on its own
		if( isset( $this->pairs[$key] ) )
			return TRUE;
		//  Key has not been found

		//  prepare Prefix Key to search for
		$key		.= $this->divider;
		//  iterate all stores Pairs
		foreach( $this->pairs as $pairKey => $pairValue )
			//  Prefix Key is found
			if( str_starts_with( $pairKey, $key ) )
				return TRUE;
		return FALSE;
	}

	/**
	 *	Removes a Value or Pair Map from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 *	@throws		InvalidArgumentException	if key is empty
	 */
	public function remove( string $key ): bool
	{
		//  no Key given
		if( 0 === strlen( trim( $key ) ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Key is set on its own
		if( isset( $this->pairs[$key] ) ){
			//  remove Pair
			unset( $this->pairs[$key] );
			return TRUE;
		}

		$count	= 0;
		//  prepare Prefix Key to search for
		$key		.= $this->divider;
		//  iterate all stores Pairs
		foreach( $this->pairs as $pairKey => $pairValue ){
			//  Prefix Key is found
			if( str_starts_with( $pairKey, $key ) ){
				//  remove Pair
				unset( $this->pairs[$pairKey] );
				$count++;
			}
		}
		return $count > 0;
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string			$key		Key in Dictionary
	 *	@param		mixed			$value		Value of Key
	 *	@param		bool			$sort		Flag: sort by Keys after Insertion
	 *	@return		bool
	 */
	public function set( string $key, mixed $value, bool $sort = TRUE ): bool
	{
		//  no Key given
		if( 0 === strlen( trim( $key ) ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Pair Map given
		if( is_array( $value ) )
			//  iterate given Pair Map
			foreach( $value as $pairKey => $pairValue )
				//  add Pair to store Pairs
				$this->pairs[$key.$this->divider.$pairKey]	= $pairValue;
		//  single Value given
		else
			//  set Pair
			$this->pairs[$key]	= $value;
		//  sort after Insertion is active
		if( $sort )
			//  sort stored Pairs by Keys
			ksort( $this->pairs );
		return TRUE;
	}
}

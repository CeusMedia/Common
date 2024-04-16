<?php /** @noinspection PhpUnused */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *
 *	Copyright (c) 2006-2024 Christian Würker (ceusmedia.de)
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
 *	@copyright		2006-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\ADT\Collection;

use ArrayAccess;
use Countable;
use InvalidArgumentException;
use Iterator;
use OutOfRangeException;
use UnexpectedValueException;

/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *	@category		Library
 *	@package		CeusMedia_Common_ADT_List
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2006-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@phpstan-consistent-constructor
 */
class Dictionary implements ArrayAccess, Countable, Iterator
{
	/**	@var		array			$pairs			Associative Array of Pairs */
	protected array $pairs;

	/**	@var		int				$position		Iterator Position */
	private int $position			= 0;

	/**	@var		boolean			$caseSensitive	Flag: be case-sensitive on pair keys */
	protected bool $caseSensitive	= TRUE;

	/**
	 *	Create a new instance.
	 *	@static
	 *	@access		public
	 *	@param		array		$array		Map if initial pairs
	 *	@return		static
	 */
	public static function create( array $array ): static
	{
		return new static( $array );
	}

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$array		Map if initial pairs
	 *	@return		void
	 */
	public function __construct( array $array = [], bool $caseSensitive = TRUE )
	{
		$this->caseSensitive	= $caseSensitive;
		$this->pairs			= $array;
	}

	/**
	 *	Casts a Value by the Type of the current Value by its Key.
	 *	@access		public
	 *	@param		mixed		$value		Value to cast
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 *	@throws		InvalidArgumentException	if value is a resource
	 *	@throws		OutOfRangeException			if key is not existing
	 *	@throws		UnexpectedValueException	if cast is not possible (like between string and array and vise versa)
	 */
	public function cast( $value, string $key )
	{
		if( strtolower( gettype( $value ) ) === "resource" )
			throw new InvalidArgumentException( 'Cannot cast resource' );
		if( !$this->has( $key ) )
			throw new OutOfRangeException( 'Invalid key "'.$key.'"' );

		//  lowercase key if dictionary is not case-sensitive
		$key		= !$this->caseSensitive ? strtolower( $key ) : $key;
		$valueType	= strtolower( gettype( $value ) );
		$pairType	= strtolower( gettype( $this->get( $key ) ) );

		$abstracts	= ['array', 'object'];
		if( in_array( $valueType, $abstracts, TRUE ) !== in_array( $pairType, $abstracts, TRUE ) )
			throw new UnexpectedValueException( 'Cannot cast '.$valueType.' to '.$pairType );
		settype( $value, $pairType );
		return $value;
	}

	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		integer
	 */
	public function count(): int
	{
		return count( $this->pairs );
	}

	/**
	 *	Returns current Value.
	 *	@access		public
	 *	@return		mixed
	 */
	#[\ReturnTypeWillChange]
	public function current()
	{
		if( $this->position >= $this->count() )
			return NULL;
		$keys	= array_keys( $this->pairs );
		return $this->pairs[$keys[$this->position]];
	}

	public function flush(): void
	{
		foreach( $this->getKeys() as $key )
			$this->remove( $key );
		$this->rewind();
	}

	/**
	 *	Returns dictionary with all pairs having a key starting with prefix.
	 *	Attention: A given prefix will be cut from pair keys.
	 *	@access		public
	 *	@param		string		$prefix			Prefix to filter keys, e.g. "mail." for all pairs starting with "mail."
	 *	@param		boolean		$caseSensitive	Flag: return list with lowercase pair keys or dictionary with no case sensitivity
	 *	@return		static						Dictionary object containing filtered pairs
	 */
	public function filterByKeyPrefix( string $prefix, bool $caseSensitive = TRUE ): static
	{
		//  assume all pairs by default
		$list	= $this->pairs;
		//  a prefix to filter keys has been given
		if( strlen( $prefix ) ){
			//  create empty list
			$list	= [];
			//  get prefix length
			$length	= strlen( $prefix );
			//  iterate all pairs
			foreach( $this->pairs as $key => $value ){
				//  pair key is shorter than prefix
				if( strlen( $key ) <= $length )
					//  skip this pair
					continue;
				$substr	= substr( $key, 0, $length );
				if( $caseSensitive )
					$match	= $substr === $prefix;
				else
					$match	= strtolower( $substr ) === strtolower( $prefix );
				//  key starts with prefix
				if( $match ){
					//  cut prefix
					$key	= substr( $key, $length );
					//  enlist pair, with case-insensitive key if needed
					$list[$key]	= $value;
				}
			}
		}
		//  return pair list as dictionary
		return new static( $list );
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		mixed		$default	Value to return if key is not set, default: NULL
	 *	@return		mixed
	 */
	public function get( string $key, $default = NULL )
	{
		if( $this->has( $key ) )
			return $this->pairs[( !$this->caseSensitive ? strtolower( $key ) : $key )];
		//  return given default, default: NULL
		return $default;
	}

	/**
	 *	Returns all Pairs of Dictionary as an Array.
	 *	Using a filter prefix, all pairs with keys starting with prefix are returned.
	 *	Attention: A given prefix will be cut from pair keys.
	 *	By default, an array is returned. Alternatively another dictionary can be returned.
	 *	@access		public
	 *	@param		string|NULL		$prefix			Prefix to filter keys, e.g. "mail." for all pairs starting with "mail."
	 *	@param		boolean			$asDictionary	Flag: return list as dictionary object instead of an array
	 *	@param		boolean			$caseSensitive	Flag: return list with lowercase pair keys or dictionary with no case sensitivity
	 *	@return		static|array	Map or dictionary object containing all or filtered pairs
	 */
	public function getAll( ?string $prefix = NULL, bool $asDictionary = FALSE, bool $caseSensitive = TRUE ): static|array
	{
		//  assume all pairs by default
		$list	= $this->pairs;
		//  a prefix to filter keys has been given
		if( NULL !== $prefix && strlen( trim( $prefix ) ) ){
			$filtered	= $this->filterByKeyPrefix( $prefix );
			if( $asDictionary )
				return $filtered;
			return $filtered->getAll();
		}
		//  a dictionary object is to be returned
		if( $asDictionary )
			//  create dictionary for pair list
			$list	= new static( $list, $caseSensitive );
		//  return pair list as array or dictionary
		return $list;
	}

	/**
	 *	Return list of pair keys.
	 *	@access		public
	 *	@return		array		List of pair keys
	 */
	public function getKeys(): array
	{
		return array_keys( $this->pairs );
	}

	/**
	 *	Returns corresponding Key of a Value if Value is in Dictionary, otherwise NULL.
	 *	@access		public
	 *	@param		mixed		$value		Value to get Key of
	 *	@return		int|string|NULL			Key of value if found, otherwise NULL
	 */
	public function getKeyOf( $value )
	{
		$key		= array_search( $value, $this->pairs, TRUE );
		return $key === FALSE ? NULL : $key;
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		boolean
	 */
	public function has( string $key ): bool
	{
		//  lowercase key if dictionary is not case-sensitive
		$key	= !$this->caseSensitive ? strtolower( $key ) : $key;
		return array_key_exists( $key, $this->pairs );
	}

	/**
	 *	@return		bool
	 */
	public function isCaseSensitive(): bool
	{
		return $this->caseSensitive;
	}

	/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		int|string|NULL
	 */
	#[\ReturnTypeWillChange]
	public function key()
	{
		$keys	= array_keys( $this->pairs );
		return $this->position < $this->count() ? $keys[$this->position] : NULL;
	}

	/**
	 *	Selects next Pair.
	 *	@access		public
	 *	@return		void
	 */
	#[\ReturnTypeWillChange]
	public function next()
	{
		$this->position++;
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		boolean
	 */
	public function offsetExists( $offset ): bool
    {
		return $this->has( $offset );
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		mixed
	 */
	#[\ReturnTypeWillChange]
	public function offsetGet( $offset )
	{
		return $this->get( $offset );
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@param		string		$value		Value of Key
	 *	@return		boolean
	 */
	#[\ReturnTypeWillChange]
	public function offsetSet( $offset, $value ): bool
    {
		return $this->set( $offset, $value );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		boolean
	 */
	#[\ReturnTypeWillChange]
	public function offsetUnset( $offset ): bool
	{
		return $this->remove( $offset );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		boolean
	 */
	public function remove( string $key ): bool
	{
		//  pair key is not existing
		if( !$this->has( $key ) )
			//  indicate miss
			return FALSE;
		//  lowercase key if dictionary is not case-sensitive
		$key	= !$this->caseSensitive ? strtolower( $key ) : $key;
		//  index of pair to be removed
		$index	= array_search( $key, array_keys( $this->pairs ), TRUE );
		//  iterator position is beyond pair
		if( $index >= $this->position )
			//  decrease iterator position since pair is removed
			$this->position--;
		//  remove pair by its key
		unset( $this->pairs[$key] );
		//  indicate hit
		return TRUE;
	}

	/**
	 *	Resets Pair Pointer.
	 *	@access		public
	 *	@return		void
	 */
	#[\ReturnTypeWillChange]
	public function rewind()
	{
		$this->position	= 0;
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		mixed		$value		Value of Key, NULL will remove pair from list
	 *	@return		boolean
	 */
	public function set( string $key, $value ): bool
	{
		//  check if pair is already existing
		if( $this->has( $key ) ){
			//  given value is NULL, which means: remove this pair
			if( is_null( $value ) )
				//  remove pair and return result of sub operation
				return $this->remove( $key );
			//  value of pair did not change
			else if( $this->get( $key ) === $value )
				//  quit and return negative because no change has taken place
				return FALSE;
		}
		//  set new value of current pair, case-insensitive if needed
		$this->pairs[( !$this->caseSensitive ? strtolower( $key ) : $key )]		= $value;
		//  indicate success
		return TRUE;
	}

	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		boolean
	 */
	public function valid(): bool
	{
		return $this->position < $this->count();
	}
}

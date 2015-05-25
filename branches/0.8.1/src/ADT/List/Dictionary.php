<?php
/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *
 *	Copyright (c) 2006-2013 Christian Würker (ceusmedia.com)
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
 *	@copyright		2006-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *	@category		cmClasses
 *	@package		ADT.List
 *	@implements		ArrayAccess
 *	@implements		Countable
 *	@implements		Iterator
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2006-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
class ADT_List_Dictionary implements ArrayAccess, Countable, Iterator
{
	/**	@var		array		$pairs			Associative Array of Pairs */
	protected $pairs			= array();
	/**	@var		array		$position		Iterator Position */
	private $position			= 0;
	/**	@var		boolean		$caseSensitive	Flag: be case sensitive on pair keys */
	protected $caseSensitive	= TRUE;
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$array		Map if initial pairs
	 *	@return		void
	 */
	public function __construct( $array = array(), $caseSensitive = TRUE )
	{
		$this->caseSensitive	= (bool) $caseSensitive;
		if( $array instanceof ADT_List_Dictionary )
			$array	= $array->getAll();
		if( is_object( $array ) )
			$array	= (array) $array;
		if( is_array( $array ) && count( $array ) )
			foreach( $array as $key => $value )
				$this->set( $key, $value );
	}

	/**
	 *	Casts a Value by the Type of the current Value by its Key.
	 *	@access		public
	 *	@param		string		$value		Value to cast
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 *	@throws		InvalidArgumentException	if value is a resource
	 *	@throws		OutOfRangeException			if key is not existing
	 *	@throws		UnexpectedValueException	if cast is not possible (like between string and array and vise versa)
	 */
	public function cast( $value, $key )
	{
		if( strtolower( gettype( $value ) ) === "resource" )
			throw new InvalidArgumentException( 'Cannot cast resource' );
		if( !$this->has( $key ) )
			throw new OutOfRangeException( 'Invalid key "'.$key.'"' );

		$key		= !$this->caseSensitive ? strtolower( $key ) : $key;								//  lowercase key if dictionary is not case sensitive
		$valueType	= strtolower( gettype( $value ) );
		$pairType	= strtolower( gettype( $this->get( $key ) ) );	

		$abstracts	= array( 'array', 'object' );
		if( in_array( $valueType, $abstracts ) !== in_array( $pairType, $abstracts ) )
			throw new UnexpectedValueException( 'Cannot cast '.$valueType.' to '.$pairType );
		settype( $value, $pairType );
		return $value;
	}

	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		integer
	 */
	public function count()
	{
		return count( $this->pairs );
	}

	/**
	 *	Returns current Value.
	 *	@access		public
	 *	@return		mixed
	 */
	public function current()
	{
		if( $this->position >= $this->count() )
			return NULL;
		$keys	= array_keys( $this->pairs );
		return $this->pairs[$keys[$this->position]];
	}

	public function flush(){
		foreach( $this->getKeys() as $key )
			$this->remove( $key );
		$this->rewind();
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		mixed		$default	Value to return if key is not set
	 *	@return		mixed
	 */
	public function get( $key, $default = NULL )
	{
		if( $this->has( $key ) )
			return $this->pairs[( !$this->caseSensitive ? strtolower( $key ) : $key )];
		return $default;
	}

	/**
	 *	Returns all Pairs of Dictionary as an Array.
	 *	Using a filter prefix, all pairs with keys starting with prefix are returned.
	 *	Attention: A given prefix will be cut from pair keys.
	 *	By default an array is returned. Alternatively another dictionary can be returned.
	 *	@access		public
	 *	@param		string		$prefix			Prefix to filter keys, e.g. "mail." for all pairs starting with "mail."
	 *	@param		boolean		$asDictionary	Flag: return list as dictionary object instead of an array
	 *	@param		boolean		$caseSensitive	Flag: return list with lowercase pair keys or dictionary with no case sensitivy
	 *	@return		array|ADT_List_Dictionary	Map or dictionary object containing all or filtered pairs
	 */
	public function getAll( $prefix = NULL, $asDictionary = FALSE, $caseSensitive = TRUE )
	{
		$list	= $this->pairs;																		//  assume all pairs by default
		if( strlen( $prefix ) ){																	//  a prefix to filter keys has been given
			$list	= array();																		//  create empty list
			$length	= strlen( $prefix );															//  get prefix length
			foreach( $this->pairs as $key => $value )												//  iterate all pairs
			{
				if( strlen( $key ) <= $length )														//  pair key is shorter than prefix
					continue;																		//  skip this pair
				if( substr( $key, 0, $length ) == $prefix ){										//  key starts with prefix
					$key	= substr( $key, $length );												//  cut prefix
					$list[( !$this->caseSensitive ? strtolower( $key ) : $key )]	= $value;		//  enlist pair, with case insensitive key if needed
				}
			}
		}
		if( $asDictionary )																			//  a dictionary object is to be returned
			$list	= new ADT_List_Dictionary( $list, $caseSensitive );								//  create dictionary for pair list
		return $list;																				//  return pair list as array or dictionary
	}

	/**
	 *	Return list of pair keys.
	 *	@access		public
	 *	@return		array		List of pair keys
	 */
	public function getKeys()
	{
		return array_keys( $this->pairs );
	}

	/**
	 *	Returns corresponding Key of a Value if Value is in Dictionary, otherwise NULL.
	 *	@access		public
	 *	@param		string		$value		Value to get Key of
	 *	@return		mixed|NULL				Key of value if found, otherwise NULL
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
	public function has( $key )
	{
		$key	= !$this->caseSensitive ? strtolower( $key ) : $key;								//  lowercase key if dictionary is not case sensitive
		return array_key_exists( $key, $this->pairs );
	}

	/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		mixed|NULL
	 */
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
	public function next()
	{
		$this->position++;
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		boolean
	 */
	public function offsetExists( $key )
	{
		return $this->has( $key );
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->get( $key );
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		string		$value		Value of Key
	 *	@return		boolean
	 */
	public function offsetSet( $key, $value )
	{
		return $this->set( $key, $value );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		boolean
	 */
	public function offsetUnset( $key )
	{
		return $this->remove( $key );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		boolean
	 */
	public function remove( $key )
	{
		if( !$this->has( $key ) )																	//  pair key is not existing
			return FALSE;																			//  indicate miss
		$key	= !$this->caseSensitive ? strtolower( $key ) : $key;								//  lowercase key if dictionary is not case sensitive
		$index	= array_search( $key, array_keys( $this->pairs ) );									//  index of pair to be removed
		if( $index >= $this->position )																//  iterator position is beyond pair
			$this->position--;																		//  decrease iterator position since pair is removed
		unset( $this->pairs[$key] );																//  remove pair by its key
		return TRUE;																				//  indicate hit
	}

	/**
	 *	Resets Pair Pointer.
	 *	@access		public
	 *	@return		void
	 */
	public function rewind()
	{
		$this->position	= 0;
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		string		$value		Value of Key, NULL will remove pair from list
	 *	@return		boolean
	 */
	public function set( $key, $value )
	{
		if( $this->has( $key ) )																	//  check if pair is already existing
		{
			if( is_null( $value ) )																	//  given value is NULL, which means: remove this pair
				return $this->remove( $key );														//  remove pair and return result of sub operation
			else if( $this->get( $key ) === $value )												//  value of pair did not change
				return FALSE;																		//  quit and return negative because no change has taken place
		}
		$this->pairs[( !$this->caseSensitive ? strtolower( $key ) : $key )]		= $value;			//  set new value of current pair, case insensitive if needed
		return TRUE;																				//  indicate success
	}

	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		boolean
	 */
	public function valid()
	{
		return $this->position < $this->count();
	}
}
?>

<?php
/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@copyright		2007-2012 Christian Würker
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
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
class ADT_List_Dictionary implements ArrayAccess, Countable, Iterator
{
	/**	@var		array		$pairs		Associative Array of Pairs */
	protected $pairs	= array();
	/**	@var		array		$position	Iterator Position */
	private $position	= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		array		$array		Map if initial pairs
	 *	@return		void
	 */
	public function __construct( $array = array() )
	{
		if( is_array( $array ) && count( $array ) )
			foreach( $array as $key => $value )
				$this->set( $key, $value );
	}

	/**
	 *	Return session pairs as string.
	 *	@access		public
	 *	@return		string
	 */
	public function __toString()
	{
		$list	= array();
		foreach( $this->pairs as $key => $value )
			$list[]	= "(".$key."=>".$value.")";
		$list	= implode( ", ", $list );
		$list	= "{".$list."}";
		return $list;
	}

	public function flush(){
		foreach( $this->getKeys() as $key )
			$this->remove( $key );
		$this->rewind();
	}
	
	public function append( $key, $value ){
		$current	= $this->get( $key );
		if( is_array( $current ) )
			$current[]	= $value;
		else
			$current	= (string)$current.$value;
		$this->set( $key, $value );
	}

	/**
	 *	Casts a Value by the Type of the current Value by its Key.
	 *	@access		public
	 *	@param		string		$value		Value to cast
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function cast( $value, $key )
	{
		$type	= gettype( $this->pairs[$key] );
		settype( $value, $type );
		return $value;
	}

	/**
	 *	Returns Size of Dictionary.
	 *	@access		public
	 *	@return		int
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
		return $this->pairs[$this->key()];
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( $this->has( $key ) )
			return $this->pairs[$key];
		return NULL;
	}

	/**
	 *	Returns all Pairs of Dictionary as an Array.
	 *	Using a filter prefix, all pairs with keys starting with prefix are returned.
	 *	Attention: A given prefix will be cut from pair keys.
	 *	By default an array is returned. Alternatively another dictionary can be returned.
	 *	@access		public
	 *	@param		string		$prefix			Prefix to filter keys, e.g. "mail." for all pairs starting with "mail."
	 *	@param		boolean		$asDictionary	Flag: return list as dictionary object instead of an array
	 *	@return		array|ADT_List_Dictionary	Map or dictionary object containing all or filtered pairs
	 */
	public function getAll( $prefix = NULL, $asDictionary = FALSE )
	{
		$list	= $this->pairs;																		//  assume all pairs by default
		if( strlen( $prefix ) ){																	//  a prefix to filter keys has been given
			$list	= array();																		//  create empty list
			$length	= strlen( $prefix );															//  get prefix length
			foreach( $this->pairs as $key => $value )												//  iterate all pairs
			{
				if( strlen( $key ) <= $length )														//  pair key is shorter than prefix
					continue;																		//  skip this pair
				if( substr( $key, 0, $length ) == $prefix )											//  key starts with prefix
					$list[substr( $key, $length )]	= $value;										//  enlist pair
			}
		}
		if( $asDictionary )																			//  a dictionary object is to be returned
			$list	= new ADT_List_Dictionary( $list );												//  create dictionary for pair list
		return $list;																				//  return pair list as array or dictionary
	}

	public function getKeys()
	{
		return array_keys( $this->pairs );
	}

	/**
	 *	Returns corresponding Key of a Value if Value is in Dictionary, otherwise NULL.
	 *	@access		public
	 *	@param		string		$value		Value to get Key of
	 *	@return		mixed
	 */
	public function getKeyOf( $value )
	{
		if( $key = array_search( $value, $this->pairs ) )
			return $key;
		return NULL;
	}

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 */
	public function has( $key )
	{
		return array_key_exists( $key, $this->pairs );
	}

	/**
	 *	Returns current Key.
	 *	@access		public
	 *	@return		mixed
	 */
	public function key()
	{
		$keys	= array_keys( $this->pairs	);
		return $keys[$this->position];
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
	 *	@return		bool
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
	 *	@return		bool
	 */
	public function offsetSet( $key, $value )
	{
		return $this->set( $key, $value );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 */
	public function offsetUnset( $key )
	{
		return $this->remove( $key );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		bool
	 */
	public function remove( $key )
	{
		if( !$this->has( $key ) )																	//  pair key is not existing
			return FALSE;																			//  indicate miss
		$index	= array_search( $key, array_keys( $this->pairs ) );									//  index of pair to be removed
		if( $this->position >= $index )																//  iterator position is beyond pair
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
	 *	@return		bool
	 */
	public function set( $key, $value )
	{
		if( $this->has( $key ) )																	//  check if pair is already existing
		{
			if( is_null( $value ) )																	//  given value is NULL, which means: remove this pair
				return $this->remove( $key );														//  remove pair and return result of sub operation
			else if( $this->pairs[$key] === $value )												//  value of pair did not change
				return FALSE;																		//  quit and return negative because no change has taken place
		}
		$this->pairs[$key]		= $value;															//  set new value of current pair
		return TRUE;																				//  indicate success
	}

	/**
	 *	Indicates whether Pair Pointer is valid.
	 *	@access		public
	 *	@return		bool
	 */
	public function valid()
	{
		return $this->position < $this->count();
	}
}
?>

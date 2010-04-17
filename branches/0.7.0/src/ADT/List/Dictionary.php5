<?php
/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
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
 *	@package		adt.list
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			27.03.2006
 *	@version		$Id$
 */
/**
 *	Dictionary is a simple Pair Structure similar to an associative Array but implementing some Interfaces.
 *	@category		cmClasses
 *	@package		adt.list
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
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

	public function __construct( $array = array() )
	{
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
	 *	@access		public
	 *	@return		array
	 */
	public function getAll( $prefix = NULL )
	{
		if( !$prefix )
			return $this->pairs;
		$list	= array();
		$length	= strlen( $prefix );
		foreach( $this->pairs as $key => $value )
		{
			if( strlen( $key ) <= $length )
				continue;
			if( substr( $key, 0, $length ) == $prefix )
			{
				$key	= substr( $key, $length );
				$list[$key]	= $value;
			}
		}
		return $list;
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
		return isset( $this->pairs[$key] );
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
		if( !isset( $this->pairs[$key] ) )
			return FALSE;
		unset( $this->pairs[$key] );
		return TRUE;
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
	 *	@param		string		$value		Value of Key
	 *	@return		bool
	 */
	public function set( $key, $value )
	{
		if( isset( $this->pairs[$key] ) && $this->pairs[$key] === $value )
			return FALSE;
		$this->pairs[$key]		= $value;
		return TRUE;
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
	
	public function __toString()
	{
		$list	= array();
		foreach( $this->pairs as $key => $value )
		{
			$list[]	= "(".$key."=>".$value.")";
		}
		$list	= implode( ", ", $list );
		$list	= "{".$list."}";
		return $list;
	}
}
?>
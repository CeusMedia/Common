<?php
/**
 *	Abstract Cache Store, can be used to implement a Data Cache.
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
 *	@package		adt.cache
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		0.1
 */
/**
 *	Abstract Cache Store, can be used to implement a Data Cache.
 *	@package		adt.cache
 *	@abstract
 *	@implements		ArrayAccess
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		0.1
 */
abstract class ADT_Cache_Store implements ArrayAccess
{
	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public function __get( $key )
	{
		return $this->get( $key );
	}
	
	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function __isset( $key )
	{
		return $this->has( $key );
	}
	
	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public function __set( $key, $value )
	{
		return $this->set( $key, $value );
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function __unset( $key )
	{
		return $this->remove( $key );
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	abstract public function get( $key );

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public function has( $key );

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function offsetExists( $key )
	{
		return $this->has( $key );
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public function offsetGet( $key )
	{
		return $this->get( $key );
	}

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public function offsetSet( $key, $value )
	{
		return $this->set( $key, $value );
	}
	
	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function offsetUnset( $key )
	{
		return $this->remove( $key );
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	abstract public function remove( $key );

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@abstract
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	abstract public function set( $key, $value );
}
?>
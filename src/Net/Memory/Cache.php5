<?php
/**
 *	Cache to store Data in Memory of a remote Server using MemCache as Store.
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
 *	@package		Net.Memory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		$Id$
 */
/**
 *	Cache to store Data in Memory of a remote Server using MemCache as Store.
 *	@category		cmClasses
 *	@package		Net.Memory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.04.2009
 *	@version		$Id$
 */
class Net_Memory_Cache extends ADT_Cache_Store
{
	public $expires		= 0;
	public $compress	= 0;
	protected $store;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$host		Host of MemCache Server
	 *	@param		string		$port		Port of MemCache Server
	 *	@return		void
	 */
	public function __construct( $host = "127.0.0.1", $port = 11211 )
	{
		if( !defined( 'MEMCACHE_COMPRESSED' ) )
			throw new RuntimeException( 'Memcache is not installed' );
		$this->store	= new Memcache;
		$this->store->connect( $host, $port );
	}

	/**
	 *	Adds another MemCache Server.
	 *	@access		public
	 *	@param		string		$host		Host of MemCache Server
	 *	@param		string		$port		Port of MemCache Server
	 *	@return		bool
	 */
	public function addServer( $host, $port )
	{
		return $this->store->addServer( $host, $port );
	}

	/**
	 *	Decrements a stored Value by its Key and a given Value or 1 by default and returns new Value.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@param		int			$value		Value to decrement by, default: 1
	 *	@return		int
	 */
	public function decrement( $key, $value = 1 )
	{
		$this->store->decrement( $key, $value );
	}
	
	/**
	 *	Removes all stored Pairs.
	 *	@access		public
	 *	@return		bool
	 */
	public function flush()
	{
		return $this->store->flush();
	}
	
	/**
	 *	Returns a stored Value by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@return		mixed
	 */
	public function get( $key )
	{
		return $this->store->get( $key );
	}

	/**
	 *	Returns statistical information of all Servers.
	 *	@access		public
	 *	@return		array
	 */
	public function getStats()
	{
		return $this->store->getExtendedStats();
	}
	
	/**
	 *	Indicates whether a Pair is stored by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@return		bool
	 */
	public function has( $key )
	{
		$value	= $this->store->get( $key );
		if( $value === FALSE )
			return $this->store->replace( $key, FALSE ) ? TRUE : FALSE;
		return TRUE;
	}

	/**
	 *	Increments a stored Value by its Key and a given Value or 1 by default and returns new Value.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@param		int			$value		Value to increment by, default: 1
	 *	@return		int
	 */
	public function increment( $key, $value = 1 )
	{
		$this->store->increment( $key, $value );
	}
	
	/**
	 *	Removes a stored Pair by its Key.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@return		bool
	 */
	public function remove( $key )
	{
		return $this->store->delete( $key );
	}
	
	/**
	 *	Stores or replaces a Pair.
	 *	@access		public
	 *	@param		string		$key		Key of Cache Pair
	 *	@param		int			$value		Value to store
	 *	@return		bool
	 */
	public function set( $key, $value )
	{
		return $this->store->set( $key, $value, 0, $this->expires );
	}
}
?>
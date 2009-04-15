<?php
/**
 *	Cache to store Data in Memory of a remote Server using MemCache as Store.
 *	@package		net.memory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
import( 'de.ceus-media.adt.cache.Store' );
/**
 *	Cache to store Data in Memory of a remote Server using MemCache as Store.
 *	@package		net.memory
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
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
	public function __construct( $host = "localhost", $port = 11211 )
	{
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
	 *	Removes all stored Pairs.
	 *	@access		public
	 *	@return		bool
	 */
	public function flush()
	{
		return $this->store->flush();
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
}
?>
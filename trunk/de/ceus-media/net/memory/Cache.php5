<?php
import( 'de.ceus-media.adt.cache.Store' );
class Net_Memory_Cache extends ADT_Cache_Store
{
	public $expire		= 3;
	public $compress	= 0;
	protected $store;

	public function __construct( $host = "localhost", $port = 11211 )
	{
		$this->store	= new Memcache;
		$this->store->connect( $host, $port );
	}

	public function has( $key )
	{
		$value	= $this->store->get( $key );
		if( $value === FALSE )
			return $this->store->replace( $key, FALSE ) ? TRUE : FALSE;
		return TRUE;
	}
	
	public function get( $key )
	{
		return $this->store->get( $key );
	}
	
	public function set( $key, $value )
	{
		return $this->store->set( $key, $value );
	}
	
	public function remove( $key )
	{
		return $this->store->delete( $key );
	}
	
	public function flush()
	{
		return $this->store->flush();
	}
}
?>
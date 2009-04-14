<?php
import( 'de.ceus-media.adt.cache.StaticStore' );
class Net_Memory_StaticCache extends ADT_Cache_StaticStore
{
	protected static $expire		= 0;
	protected static $compress		= FALSE;
	protected static $connection	= NULL;
	
	public static function connect( $host = "localhost", $port = 11211 )
	{
		self::$connection	= new Memcache;
		self::$connection->connect( $host, $port );
	}
	
	public static function get( $key )
	{
		if( !self::$connection )
			throw new RuntimeException( 'Not connected' );
		$value	= self::$connection->get( $key );
		if( $value === FALSE )
			return self::$connection->replace( $key, FALSE ) ? FALSE : NULL; 
		return $value;
	}
	
	public static function has( $key )
	{
		if( !self::$connection )
			throw new RuntimeException( 'Not connected' );
		$value	= self::$connection->get( $key );
		if( $value === FALSE )
			return self::$connection->replace( $key, FALSE ) ? TRUE : NULL; 
		return TRUE;
	}
	
	public static function set( $key, $value, $expire = NULL )
	{
		if( !self::$connection )
			throw new RuntimeException( 'Not connected' );
		if( is_null( $expire ) )
			$expire	= self::$expire;
		return self::$connection->set( $key, $value, self::$compress, $expire );
	}
	
	public static function remove( $key )
	{
		if( !self::$connection )
			throw new RuntimeException( 'Not connected' );
		return self::$connection->delete( $key );
	}
	
	public static function getExtendedStats()
	{
		if( !self::$connection )
			throw new RuntimeException( 'Not connected' );
		return self::$connection->getExtendedStats();
	}	
}
?>
<?php
/**
 *	...
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
 *	@category		cmClasses
 *	@package		net.memory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 */
import( 'de.ceus-media.adt.cache.StaticStore' );
/**
 *	...
 *	@category		cmClasses
 *	@package		net.memory
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@todo			Code Doc
 */
class Net_Memory_StaticCache extends ADT_Cache_StaticStore
{
	protected static $expire		= 0;
	protected static $compress		= FALSE;
	protected static $connection	= NULL;
	
	public static function connect( $host = "127.0.0.1", $port = 11211 )
	{
		if( !defined( 'MEMCACHE_COMPRESSED' ) )
			throw new RuntimeException( 'Memcache is not installed' );
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
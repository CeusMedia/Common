<?php
/**
 *	Cache to store Data in Files statically.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
import( 'de.ceus-media.adt.cache.StaticStore' );
import( 'de.ceus-media.file.Editor' );
/**
 *	Cache to store Data in Files statically.
 *	@package		file
 *	@extends		ADT_Cache_StaticStore
 *	@implements		Countable
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
class File_StaticCache extends ADT_Cache_StaticStore implements Countable
{
	/**	@var		array		$data			Memory Cache */
	protected static $data			= array();

	/**	@var		string		$path			Path to Cache Files */
	protected static $path			= NULL;

	/**
	 *	Counts all Elements in Cache.
	 *	@access		public
	 *	@return		int
	 */
	public function count()
	{
		return count( $this->data );	
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public static function get( $key )
	{
		if( self::$path === NULL )
			throw new RuntimeException( 'Cache has not been initiated.' );
		if( isset( self::$data[$key] ) )
			return self::$data[$key];
		if( !self::has( $key ) )
			return NULL;
		$uri		= self::getUriForKey( $key );
		$content	= File_Editor::load( $uri );
		$value		= unserialize( $content );
		self::$data[$key]	= $value;
		return $value;
	}

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public static function has( $key )
	{
		if( self::$path === NULL )
			throw new RuntimeException( 'Cache has not been initiated.' );
		if( isset( self::$data[$key] ) )
			return self::$data[$key];
		$uri	= self::getUriForKey( $key );
		return file_exists( $uri );		
	}

	protected static function getUriForKey( $key )
	{
		return self::$path.urlencode( $key );
	}

	public static function init( $path )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		self::$path	= $path;
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public static function remove( $key )
	{
		if( self::$path === NULL )
			throw new RuntimeException( 'Cache has not been initiated.' );
		$uri	= self::getUriForKey( $key );
		@unlink( $uri );
		unset( self::$data[$key] );
	}

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public static function set( $key, $value )
	{
		if( self::$path === NULL )
			throw new RuntimeException( 'Cache has not been initiated.' );
		$uri		= self::getUriForKey( $key );
		$content	= serialize( $value );
		File_Editor::save( $uri, $content );
		self::$data[$key]	= $value;
	}
}
?>
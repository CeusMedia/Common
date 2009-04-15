<?php
/**
 *	Cache to store Data in Files.
 *	@package		file
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
import( 'de.ceus-media.file.Editor' );
import( 'de.ceus-media.adt.cache.Store' );
/**
 *	Cache to store Data in Files.
 *	@package		file
 *	@extends		ADT_Cache_Store
 *	@implements		Countable
 *	@uses			File_Editor
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.04.2009
 *	@version		0.1
 */
class File_Cache extends ADT_Cache_Store implements Countable
{
	/**	@var		array		$data			Memory Cache */
	protected $data				= array();

	/**	@var		string		$path			Path to Cache Files */
	protected $path;
	
	/**	@var		int			$expires		Cache File Lifetime in Seconds */
	protected $expires			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Cache Files
	 *	@return		void
	 */
	public function __construct( $path, $expires = 0 )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path		= $path;
		$this->expires	= $expires;
	}
	
	/**
	 *	Removes all expired Cache Files.
	 *	@access		public
	 *	@param		int			$expires		Cache File Lifetime in Seconds
	 *	@return		bool
	 */
	public function cleanUp( $expires = 0 )
	{
		$expires	= $expires ? $expires : $this->expires;
		if( !$expires )
			throw new InvalidArgumentException( 'No expire time given or set on construction.' );

		$number	= 0;
		$index	= new DirectoryIterator( $this->path );
		foreach( $index as $entry )
		{
			if( $entry->isDot() || $entry->isDir() )
				continue;
			$pathName	= $entry->getPathname();
			if( substr( $pathName, -7 ) !== ".serial" )
				continue;
			if( $this->isExpired( $pathName, $expires ) )
				$number	+= (int) @unlink( $pathName );
		}
		return $number;
	}

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
	 *	Removes all Cache Files.
	 *	@access		public
	 *	@return		bool
	 */
	public function flush()
	{
		$index	= new DirectoryIterator( $this->path );
		$number	= 0;
		foreach( $index as $entry )
		{
			if( $entry->isDot() || $entry->isDir() )
				continue;
			if( substr_compare( $entry->getFilename(), -7 ) == ".serial" )
				$number	+= (int) @unlink( $entry->getPathname() );
		}
		return $number;
	}

	/**
	 *	Returns a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		$uri		= $this->getUriForKey( $key );
		if( !$this->isValidFile( $uri ) )
			return NULL;
		$content	= File_Editor::load( $uri );
		$value		= unserialize( $content );
		$this->data[$key]	= $value;
		return $value;
	}

	/**
	 *	Returns URI of Cache File from its Key.
	 *	@access		protected
	 *	@param		string		$key			Key of Cache File
	 *	@return		string
	 */
	protected function getUriForKey( $key )
	{
		return $this->path.base64_encode( $key ).".serial";
	}

	/**
	 *	Indicates wheter a Value is in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function has( $key )
	{
		if( isset( $this->data[$key] ) )
			return TRUE;
		$uri	= $this->getUriForKey( $key );
		return $this->isValidFile( $uri );
	}

	/**
	 *	Indicates whether a Cache File is existing and not expired.
	 *	@access		protected
	 *	@param		string		$uri			URI of Cache File
	 *	@return		bool
	 */
	protected function isValidFile( $uri )
	{
		if( !file_exists( $uri ) )
			return FALSE;
		if( !$this->expires )
			return TRUE;
		return !$this->isExpired( $uri, $this->expires );
	}

	/**
	 *	Indicates whether a Cache File is expired.
	 *	@access		protected
	 *	@param		string		$uri			URI of Cache File
	 *	@return		bool
	 */
	protected function isExpired( $uri, $expires )
	{
		$edge	= time() - $expires;
		return filemtime( $uri ) <= $edge;
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		bool
	 */
	public function remove( $key )
	{
		$uri	= $this->getUriForKey( $key );
		unset( $this->data[$key] );
		return @unlink( $uri );
	}

	/**
	 *	Stores a Value in Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@param		mixed		$value			Value to store
	 *	@return		void
	 */
	public function set( $key, $value )
	{
		$uri		= $this->getUriForKey( $key );
		$content	= serialize( $value );
		File_Editor::save( $uri, $content );
		$this->data[$key]	= $value;
	}
}
?>
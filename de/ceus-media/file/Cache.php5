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

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Path to Cache Files
	 *	@return		void
	 */
	public function __construct( $path )
	{
		if( !file_exists( $path ) )
			throw new RuntimeException( 'Path "'.$path.'" is not existing.' );
		$this->path	= $path;
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
		if( !file_exists( $uri ) )
			return NULL;
		$content	= File_Editor::load( $uri );
		$value		= unserialize( $content );
		$this->data[$key]	= $value;
		return $value;
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
		return file_exists( $uri );
	}

	protected function getUriForKey( $key )
	{
		return $this->path.urlencode( $key );
	}

	/**
	 *	Removes a Value from Cache by its Key.
	 *	@access		public
	 *	@param		string		$key			Key of Cache File
	 *	@return		void
	 */
	public function remove( $key )
	{
		$uri	= $this->getUriForKey( $key );
		@unlink( $uri );
		unset( $this->data[$key] );
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
<?php
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.dom.Storage' );
/**
 *	Storage with unlimited depth to store pairs of data in XML Files using a Cache.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			XML_DOM_Storage
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
/**
 *	Storage with unlimited depth to store pairs of data in XML Files using a Cache.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			XML_DOM_Storage
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			26.12.2005
 *	@version		0.1
 */
class XML_DOM_CacheStorage
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	filename		URI of XML File
	 *	@return 		void
	 */
	public function __construct( $filename )
	{
		$this->_store	= new XML_DOM_Storage( $filename );
		$this->setOption( "useCache", true );
		$this->setOption( 'pathCache', dirname( $filename )."/cache/");
		$this->setOption( 'notify', false );
	}
	
	/**
	 *	Returns value of a Path in the Storage.
	 *	@access		public
	 *	@param		string	path		Path to stored Value
	 *	@param		array	array	current Position in Storage Array
	 *	@return 		mixed
	 */
	function get( $path, $array = NULL )
	{
		return $this->_store->get( $path, $array );
	}
	
	/**
	 *	Returns Option of Storage.
	 *	@access		public
	 *	@param		string		key		Key of Option
	 *	@return 		mixed
	 */
	function getOption( $key )
	{
		return $this->_store->getOption( $key );
	}
	
	/**
	 *	Reads XML File into array for Storage Operations.
	 *	@access		public
	 *	@return 		void
	 */
	function read()
	{
		$cache	= $this->getOption( 'pathCache' ).basename( $this->_store->_filename ).".cache";
		$filename	= $this->_store->_filename;
			
		if( file_exists( $cache ) && file_exists( $filename ) && filemtime( $cache ) >= filemtime( $filename ) )
		{
			if( $this->getOption( "notify" ) )
				trigger_error( "reading '".$this->_store->_filename."' from cache.", E_USER_NOTICE );
			$file		= new File( $cache );
			$serial	= $file->readString();
			$this->_store->fromArray( unserialize( $serial ) );
		}
		else
		{
			if( $this->getOption( "notify" ) )
				trigger_error( "reading '".$this->_store->_filename."' from file, writing cache.", E_USER_NOTICE );
			$this->_store->read();
			$this->_writeCache();
		}
	}
	
	/**
	 *	Sets a Value in the Storage by its Path.
	 *	@access		public
	 *	@param		string	path		Path to value
	 *	@param		mixed	value	Value to set at Path
	 *	@return 		void
	 */
	function set( $path, $value )
	{
		$this->_store->set( $path, $value );
	}
	
	/**
	 *	Sets Option of Storage.
	 *	@access		public
	 *	@param		string		key		Key of Option
	 *	@param		string		value		Value of Option to set
	 *	@return 		void
	 */
	function setOption( $key, $value )
	{
		$this->_store->setOption( $key, $value );
	}
	
	/**
	 *	Returns Storage as Array.
	 *	@access		public
	 *	@return 		array
	 */
	function toArray()
	{
		return $this->_store->toArray();
	}

	/**
	 *	Writes XML File from Storage.
	 *	@access		public
	 *	@return 		void
	 */
	function write()
	{
		$this->_store->write();
	}
	
	/**
	 *	Writes XML Cache File from Storage.
	 *	@access		private
	 *	@return 		void
	 */
	function _writeCache()
	{
		$cache	= $this->getOption( 'pathCache' ).basename( $this->_store->_filename ).".cache";
		$serial	= serialize( $this->_store->toArray() );
		$file		= new File( $cache, 0755 );
		$file->writeString( $serial );
	}
}
?>
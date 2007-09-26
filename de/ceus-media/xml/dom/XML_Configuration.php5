<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileReader' );
import( 'de.ceus-media.xml.dom.XML_DOM_FileWriter' );
/**
 *	Reads and writes Configurations via XML.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		Object
 *	@uses			File
 *	@uses			XML_DOM_Reader
 *	@uses			XML_DOM_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.4
 */
/**
 *	Reads and writes Configurations via XML.
 *	@package		xml
 *	@subpackage		dom
 *	@extends		Object
 *	@uses			File
 *	@uses			XML_DOM_Reader
 *	@uses			XML_DOM_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.2005
 *	@version		0.4
 */
class XML_Configuration extends OptionObject
{
	/**	@var	array		_config		Array of configurations */
	var $_config	= array();
	/**	@var	array		_types		Types for value casting */
	var $_types	= array(
		"int",
		"integer",
		"double",
		"string",
		"bool",
		"boolean"
		);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		filename		URI of configration File
	 *	@param		bool			useCache	Flag: use Caching
	 *	@return		void
	 */
	public function __construct( $filename, $useCache = false )
	{
		parent::__construct();
		$this->_filename	= $filename;
		$this->setOption( 'useCache', $useCache );
		$this->setOption( 'pathCache', dirname( $filename )."/cache/");
		$this->setOption( 'notify', false );
	}

	/**
	 *	Returns a configuration value in a section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@param		string		key			Key of configuration
	 *	@return		string
	 */
	function getConfigValue( $section, $key )
	{
		return $this->_config[$section][$key];
	}
	
	function getKeys( $section = false )
	{
		if( $section && in_array( $section, array_keys( $this->_config ) ) )
			return array_keys( $this->_config[$section] );
		return array_keys( $this->_config );
	}

	/**
	 *	Sets a configuration value in a section.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@param		string		key			Key of configuration
	 *	@param		string		value		Value of configuration
	 *	@return		string
	 */
	function setConfigValue( $section, $key, $value )
	{
		$this->_config[$section][$key] = $value;
	}
	
	/**
	 *	Reads a configuration.
	 *	@access		public
	 *	@return		void
	 */
	function read()
	{
		if( $this->getOption( 'useCache' ) )
		{
			$cache	= $this->getOption( 'pathCache' ).basename( $this->_filename ).".cache";
			$filename	= $this->getOption( 'pathConfig' ).$this->_filename;
			
			if( file_exists( $cache ) && file_exists( $filename ) && filemtime( $cache ) >= filemtime( $filename ) )
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$this->_filename."' from cache", E_USER_NOTICE );
				return $this->_readCache();
			}
			else
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$this->_filename."' from file, writing cache", E_USER_NOTICE );
				if( $this->_readXML() )
					$this->_writeCache();
				return true;
			}
		}
		else
		{
			if( $this->getOption( "notify" ) )
				trigger_error( "reading '".$this->_filename."' from file", E_USER_NOTICE );
			return $this->_readXML();
		}
	
	}

	/**
	 *	Returns all configuration values.
	 *	@access		public
	 *	@return		array
	 */
	function getConfigValues()
	{
		return $this->_config;
	}
	
	/**
	 *	Reads configuration from cache.
	 *	@access		private
	 *	@return		void
	 */
	function _clearCache()
	{	
		$cache	= $this->getOption( 'pathCache' ).basename( $this->_filename ).".cache";
		@unlink( $cache );
	}

	/**
	 *	Reads configuration from cache.
	 *	@access		private
	 *	@return		void
	 */
	function _readCache()
	{	
		$cache	= $this->getOption( 'pathCache' ).basename( $this->_filename ).".cache";
		$file			= new File( $cache );
		$content		= $file->readString();
		$this->_config	= unserialize( $content );
	}

	/**
	 *	Writes configuration to cache.
	 *	@access		private
	 *	@return		void
	 */
	function _writeCache()
	{
		$path	= $this->getOption( 'pathCache' );
		if( !is_dir( $path ) )
			mkdir( $path );
		$cache	= $path.basename( $this->_filename ).".cache";
		$content	= serialize( $this->getConfigValues() );
		$time	= filemtime( $this->getOption( 'pathConfig' ).$this->_filename );
		$file		= new File( $cache, 0770 );
		$file->writeString( $content );
//		touch( $cache, $time );
	}

	/**
	 *	Reads configuration.
	 *	@access		private
	 *	@return		void
	 */
	function _readXML()
	{
		$filename	= $this->getOption( 'pathConfig' ).$this->_filename;
		$xr		= new XML_DOM_FileReader();
		if( $xr->loadFile( $filename, true, true ) )
		{
			$tree	= $xr->parse();
			foreach( $tree->getChildren() as $section )
			{
				$section_name = $section->getAttribute( 'name' );
				foreach( $section->getChildren() as $value )
				{
					$value_type	= $value->getAttribute( 'type' );
					$value_name	= $value->getAttribute( 'name' );
					$value_value	= $this->_cast( $value->getContent(), $value_type);
					$this->setConfigValue( $section_name, $value_name, $value_value );
				}
			}
			return true;

		}
		return false;
	}

	/**
	 *	Casts a value to a type.
	 *	@access		private
	 *	@param		string		value		Value to cast
	 *	@param		string		type			Type to cast to
	 *	@return		mixed
	 */
	function _cast( $value, $type )
	{
		if ( in_array( $type, $this->_types) )
		{
			settype( $value, $type );
			return $value;
		}
		else
			trigger_error( "XML_Configuration::_cast: Type '$type' is not supported.", E_USER_ERROR );
	}
}
?>
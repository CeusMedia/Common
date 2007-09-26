<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.folder.IndexFolder' );
import( 'de.ceus-media.file.PhpConfiguration' );
import( 'de.ceus-media.file.ini.IniConfiguration' );
import( 'de.ceus-media.xml.dom.XML_Configuration' );
import( 'de.ceus-media.xml.wddx.WDDX_Configuration' );
/**
 *	Configuration Reader for several formats (xml, wddx, ini, php).
 *	@package		file
 *	@extends		OptionObject
 *	@uses			IndexFolder
 *	@uses			PhpConfiguration
 *	@uses			IniConfiguration
 *	@uses			XML_Configuration
 *	@uses			WDDX_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Configuration Reader for several formats (xml, wddx, ini, php).
 *	@package		file
 *	@extends		OptionObject
 *	@uses			IndexFolder
 *	@uses			PhpConfiguration
 *	@uses			IniConfiguration
 *	@uses			XML_Configuration
 *	@uses			WDDX_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class FileConfiguration extends OptionObject
{
	/**	@var	array	_data		... */	
	var $_data	= array();
	/**	@var	array	_readers		Readers for serveral formats */	
	var $_readers	= array(
		"xml"	=> "XML_Configuration",
		"wddx"	=> "WDDX_Configuration",
		"ini"		=> "IniConfiguration",
		"php"	=> "PhpConfiguration",
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct( $useCache = false, $verbose = false )
	{
		$options['useCache']	= $useCache;
		$options['pathConfig']	= "config/";
		$options['pathCache']	= $options['pathConfig']."cache/";
		$options['verbose']		= $verbose;
		$options['notify']		= false;
		parent::__construct( $options );
	}

	/**
	 *	Loads a configuration and stores data.
	 *	@access		public
	 *	@param		string		filename		URI of configuration File
	 *	@param		string		section		Section
	 *	@return		void
	 */
	function loadConfig( $filename, $section = false )
	{
		if( file_exists( $this->getOption( "pathConfig").$filename ) )
		{
			$info	= pathinfo( $filename );
			$ext		= $info['extension'];
			$reader	= new $this->_readers[$ext]( $filename );
			$reader->setOption( 'notify', true );
			foreach( $this->getOptions() as $key => $value )
				$reader->setOption( $key , $value );
			$reader->read();
			$config = $reader->getConfigValues();
			if( $section )
				$config = array ( $section => $config );
			$this->_data = array_merge( $this->_data, $config );
		}
		else if( $this->getOption( 'verbose' ) )
			trigger_error( "Configuration File '".$filename."' does not exist.", E_USER_WARNING );
	}
	
	/**
	 *	Returns a configuration Value by key path ( section.key ).
	 *	@access		public
	 *	@param		string		filename		URI of configuration File
	 *	@param		string		section		Section
	 *	@return		void
	 */
	function get( $section_key)
	{
		$parts = explode (".", $section_key);
		$section	= $parts[0];
		if ( count( $parts ) > 1 )
		{
			$key = implode (".", array_slice( $parts, 1 ) );
			return $this->getConfigValue( $section, $key );
		}
		else
		{
			return $this->getConfigValue( $section_key );
		}
	}

	/**
	 *	Returns a configuration value of in section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@param		string		key			Key of configuration
	 *	@return		string
	 */
	function getConfigValue( $section, $key = false )
	{
		if( $key )
		{
			if( isset( $this->_data[$section][$key] ) )
				return $this->_data[$section][$key];
		}
		else if( isset( $this->_data[$section] ) )
				return $this->_data[$section];
		return false;
	}

	/**
	 *	Returns all configuration values
	 *	@access		public
	 *	@return		array
	 */
	function getConfigValues()
	{
		return $this->_data;
	}

	/**
	 *	Returns a configuration section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@return		string
	 */
	function getSection( $section )
	{
		if( isset( $this->_data[$section] ) )
			return $this->_data[$section];
		return false;
	}

	/**
	 *	Removes all Files in cache.
	 *	@access		public
	 *	@return		void
	 */
	function clearCache()
	{
		$path	= $this->getOption( "pathCache" );
		$if	= new IndexFolder( $path );
		foreach( $if->getFileList() as $file)
			unlink( $path.$file );
	}
}
?>
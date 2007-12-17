<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.folder.IndexFolder' );
import( 'de.ceus-media.file.PhpConfiguration' );
import( 'de.ceus-media.file.ini.Configuration' );
import( 'de.ceus-media.xml.dom.Configuration' );
import( 'de.ceus-media.xml.wddx.Configuration' );
/**
 *	Configuration Reader for several formats (xml, wddx, ini, php).
 *	@package		file
 *	@extends		ADT_OptionObject
 *	@uses			IndexFolder
 *	@uses			PhpConfiguration
 *	@uses			File_INI_Configuration
 *	@uses			XML_DOM_Configuration
 *	@uses			XML_WDDX_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.5
 */
/**
 *	Configuration Reader for several formats (xml, wddx, ini, php).
 *	@package		file
 *	@extends		ADT_OptionObject
 *	@uses			IndexFolder
 *	@uses			PhpConfiguration
 *	@uses			IniConfiguration
 *	@uses			XML_Configuration
 *	@uses			WDDX_Configuration
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.5
 */
class File_Configuration extends ADT_OptionObject
{
	/**	@var	array	$data			Configuration Data */	
	protected $data		= array();
	/**	@var	array	$readers		Readers for serveral formats */	
	protected $readers	= array(
		"xml"	=> "XML_DOM_Configuration",
		"wddx"	=> "XML_WDDX_Configuration",
		"ini"	=> "File_INI_Configuration",
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
	public function loadConfig( $filename, $section = false )
	{
		if( file_exists( $this->getOption( "pathConfig").$filename ) )
		{
			$info	= pathinfo( $filename );
			$ext	= $info['extension'];
			$class	= $this->readers[$ext];
			$reader	= new $class( $filename );
			$reader->setOption( 'notify', true );
			foreach( $this->getOptions() as $key => $value )
				$reader->setOption( $key , $value );
			$reader->read();
			$config = $reader->getConfigValues();
			if( $section )
				$config = array ( $section => $config );
			$this->data = array_merge( $this->data, $config );
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
	public function get( $section_key)
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
	public function getConfigValue( $section, $key = false )
	{
		if( $key )
		{
			if( isset( $this->data[$section][$key] ) )
				return $this->data[$section][$key];
		}
		else if( isset( $this->data[$section] ) )
				return $this->data[$section];
		return false;
	}

	/**
	 *	Returns all configuration values
	 *	@access		public
	 *	@return		array
	 */
	public function getConfigValues()
	{
		return $this->data;
	}

	/**
	 *	Returns a configuration section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@return		string
	 */
	public function getSection( $section )
	{
		if( isset( $this->data[$section] ) )
			return $this->data[$section];
		return false;
	}

	/**
	 *	Removes all Files in cache.
	 *	@access		public
	 *	@return		void
	 */
	public function clearCache()
	{
		$path	= $this->getOption( "pathCache" );
		$if	= new IndexFolder( $path );
		foreach( $if->getFileList() as $file)
			unlink( $path.$file );
	}
}
?>
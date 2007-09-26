<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.File' );
/**
 *	Reads and writes Configurations via XML.
 *	@package		file
 *	@extends		OptionObject
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Reads and writes Configurations via XML.
 *	@package		file
 *	@extends		OptionObject
 *	@uses			File
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class PhpConfiguration extends OptionObject
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
		$this->_filename	= realpath( $filename );
		$this->setOption( 'useCache', $useCache );
		$this->setOption( 'pathCache', dirname( realpath( $filename ) )."/cache/");
	}

	/**
	 *	Returns a configuration value in a section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@param		string		key			Key of configuration
	 *	@return		mixed
	 */
	function getConfigValue( $section, $key )
	{
		return $this->_config[$key];
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
			$filename	= $this->getOption( 'pathCache' ).basename( $this->_filename ).".cache";
			if( file_exists( $filename ) && filemtime( $filename ) == filemtime( $this->_filename ) )
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from cache.", E_USER_NOTICE );
				return $this->_readCache( $filename );
			}
			else
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from file, writing cache.", E_USER_NOTICE );
				$this->_readPhp();
				return $this->_writeCache( $filename );
			}
		}
		if( $this->getOption( "notify" ) )
			trigger_error( "reading '".$filename."' from file.", E_USER_NOTICE );
		return $this->_readPhp();
	
	}
	
	/**
	 *	Reads configuration from cache.
	 *	@access		private
	 *	@param		string		filename		URI of configration File
	 *	@return		void
	 */
	function _readCache( $filename )
	{
		$file			= new File( $filename );
		$content		= $file->readString();
		$this->_config	= unserialize( $content );
	}

	/**
	 *	Writes configuration to cache.
	 *	@access		private
	 *	@param		string		filename		URI of configration File
	 *	@return		void
	 */
	function _writeCache( $filename )
	{
		$file		= new File( $filename );
		$content	= serialize( $this->getConfigValues() );
		$file->writeString( $content );
		touch( $filename, filemtime( $this->getOption( 'pathConfig' ).$this->_filename ) );
	}

	/**
	 *	Reads configuration.
	 *	@access		private
	 *	@return		void
	 */
	function _readPhp()
	{
		require_once( $this->getOption( 'pathConfig' ).$this->_filename );
		foreach($config as $section => $section_data)
			foreach($section_data as $key => $value)
				$this->setConfigValue( $section, $key, $value);
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
	 *	Saves a configuration.
	 *	@access		public
	 *	@param		string		filename		URI of configuration file
	 *	@return		void
	 */
	function save( $filename = false)
	{
		if( !$filename )
			$filename = $this->_filename;
		$lines	= array();
		$lines[]	= "<?php";
		$lines[]	= "$"."config	= array(";
		foreach( $this->getConfigValues() as $section_name => $section_data)
		{
			$lines[]	= "	'".$section_name."'	=> array(";
			foreach( $section_data as $key => $value )
			{
				$type	= gettype( $value );
				if( $type == "string")
					$lines[]	= "		'".$key."'	=> \"".$value."\",";
				else
				{
					if( $type == "boolean")
						$value	= $value ? "true" : "false";
					$lines[]	= "		'".$key."'	=> ".$value.",";
				}
			}
			$lines[]	= "	),";
		}
		$lines[]	= ");";
		$lines[]	= "?>";
		$file	= new File( $filename, 0777 );
		$file->writeArray( $lines );
	}
}
?>
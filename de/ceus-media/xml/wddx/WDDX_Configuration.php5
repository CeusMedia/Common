<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.File' );
import( 'de.ceus-media.xml.wddx.WDDX_FileReader' );
import( 'de.ceus-media.xml.wddx.WDDX_FileWriter' );
/**
 *	Reads and writes Configurations via WDDX.
 *	@package		file
 *	@subpackage		ini
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			IniReader
 *	@uses			IniCreator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Reads and writes Configurations via WDDX.
 *	@package		file
 *	@subpackage		ini
 *	@extends		OptionObject
 *	@uses			File
 *	@uses			IniReader
 *	@uses			IniCreator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class WDDX_Configuration extends OptionObject
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
	 *	@return		string
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
			if( file_exists( $filename ) && file_exists( $this->_filename ) && filemtime( $filename ) == filemtime( $this->getOption( 'pathConfig' ).$this->_filename ) )
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from cache.", E_USER_NOTICE );
				return $this->_readCache( $filename );
			}
			else
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from file, writing caching.", E_USER_NOTICE );
				$this->_readWDDX();
				return $this->_writeCache( $filename );
			}
		}
		if( $this->getOption( "notify" ) )
			trigger_error( "reading '".$filename."' from file.", E_USER_NOTICE );
		return $this->_readWDDX();
	
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
	function _readWDDX()
	{
		$wr	= new WDDX_FileReader( $this->getOption( 'pathConfig' ).$this->_filename );
		$content = $wr->read();
		$this->_config = $wr->read();
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
	function save()
	{
		if( !$filename )
			$filename = $this->_filename;
		$ww		= new WDDX_FileWriter( "wddx_test" );
		foreach( $this->getConfigValues() as $section_name => $section_data )
			foreach( $section_data as $key => $value)
				$ww->add( $key, $value );
		$ww->write( );
	}
}
?>
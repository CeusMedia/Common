<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.file.ini.IniCreator' );
/**
 *	Reads and writes Configurations via INI.
 *	@package		file.ini
 *	@extends		ADT_OptionObject
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			File_INI_Reader
 *	@uses			File_INI_Creator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
/**
 *	Reads and writes Configurations via INI.
 *	@package		file.ini
 *	@extends		ADT_OptionObject
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			File_INI_Reader
 *	@uses			File_INI_Creator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.4
 */
class File_INI_Configuration extends ADT_OptionObject
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
	}

	/**
	 *	Returns a configuration value in a section by its key.
	 *	@access		public
	 *	@param		string		section		Section
	 *	@param		string		key			Key of configuration
	 *	@return		string
	 */
	public function getConfigValue( $section, $key )
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
	public function setConfigValue( $key, $value, $section = false )
	{
		if( $section )
			$this->_config[$section][$key] = $value;
		else
			$this->_config[$key] = $value;
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
			if( file_exists( $filename ) && file_exists( $this->_filename) && filemtime( $filename ) >= filemtime( $this->_filename ) )
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from cache.", E_USER_NOTICE );
				return $this->_readCache( $filename );
			}
			else
			{
				if( $this->getOption( "notify" ) )
					trigger_error( "reading '".$filename."' from file, writing cache.", E_USER_NOTICE );
				$this->_readIni();
				return $this->_writeCache( $filename );
			}
		}
		if( $this->getOption( "notify" ) )
			trigger_error( "reading '".$filename."' from file.", E_USER_NOTICE );
		$this->_readIni();
	}
	
	/**
	 *	Reads configuration from cache.
	 *	@access		private
	 *	@param		string		filename		URI of configration File
	 *	@return		void
	 */
	function _readCache( $filename )
	{
		$file			= new File_Reader( $filename );
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
		$file		= new File_Writer( $filename, 0770 );
		$content	= serialize( $this->getConfigValues() );
		$file->writeString( $content );
//		touch( $filename, filemtime( $this->getOption( 'pathConfig' ).$this->_filename ) );
	}

	/**
	 *	Reads configuration.
	 *	@access		private
	 *	@return		void
	 */
	function _readIni()
	{
		$ir		= new File_INI_Reader( $this->getOption( 'pathConfig' ).$this->_filename, true );
		$data	= $ir->toCommentedArray( true );
		if( $ir->usesSections() )
		{
			foreach($data as $section => $section_data)
			{
				foreach($section_data as $config => $data)
				{
					$key	= $data['key'];
					$type	= $data['comment'];
					$value	= $data['value'];
					if( $type )
						$value	= $this->_cast( $value, $type );
					$this->setConfigValue( $key, $value, $section );
				}
			}
		}
		else
		{
			foreach($data as $config => $data)
			{
				$key	= $data['key'];
				$type	= $data['comment'];
				$value	= $data['value'];
				if( $type )
					$value	= $this->_cast( $value, $type );
				$this->setConfigValue( $key, $value );
			}
		}
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
			trigger_error( "Type '".$type."' is not supported.", E_USER_WARNING );
	}

	/**
	 *	Returns all configuration values.
	 *	@access		public
	 *	@return		array
	 */
	public function getConfigValues()
	{
		return $this->_config;
	}

	/**
	 *	Saves a configuration.
	 *	@access		public
	 *	@param		string		filename		URI of configuration file
	 *	@return		void
	 */
	public function save( $filename = false)
	{
		if( !$filename )
			$filename = $this->_filename;
		$ic	= new File_INI_Creator ( true );
		foreach( $this->getConfigValues() as $section_name => $section_data)
		{
			$ic->addSection( $section_name );
			foreach( $section_data as $key => $value )
			{
				$type	= gettype( $value );
				if( $type == "boolean")
					$value	= $value ? "true" : "false";
				$ic->addPropertyToSection( $key, $value, $type, $section_name);	
			}
		}
		$ic->write( $filename );
	}
}
?>
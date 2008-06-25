<?php
import( 'de.ceus-media.adt.OptionObject' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.xml.wddx.WDDX_FileReader' );
import( 'de.ceus-media.xml.wddx.WDDX_FileWriter' );
/**
 *	Reads and writes Configurations via WDDX.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_FileReader
 *	@uses			XML_WDDX_File_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.6
 */
/**
 *	Reads and writes Configurations via WDDX.
 *	@package		xml.wddx
 *	@uses			XML_WDDX_FileReader
 *	@uses			XML_WDDX_File_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.07.02005
 *	@version		0.6
 */
class XML_WDDX_Configuration
{
	/**	@var		array		$config			Array of configurations */
	protected $config	= array();
	/**	@var		string		$fileName		File Name of WDDX File */
	protected $fileName	= array();
	/**	@var		string		$pathCache		Path to Cache */
	protected $pathCache	= array();
	/**	@var		array		$types			Types for value casting */
	protected $types	= array(
		"int",
		"integer",
		"double",
		"string",
		"bool",
		"boolean"
		);
	/**	@var		bool			$useCache	Flag: use Cache */
	protected $useCache	= array();

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		URI of configration File
	 *	@param		bool		$useCache		Flag: use Caching
	 *	@return		void
	 */
	public function __construct( $fileName, $useCache = false )
	{
		$this->fileName		= realpath( $fileName );
		$this->useCache		= $useCache );
		$this->pathCache	= dirname( realpath( $fileName ) )."/cache/";
		$this->read():
	}

	/**
	 *	Returns a configuration value in a section by its key.
	 *	@access		public
	 *	@param		string		$section		Section
	 *	@param		string		$key			Key of configuration
	 *	@return		string
	 */
	public function getConfigValue( $section, $key )
	{
		return $this->config[$key];
	}

	/**
	 *	Returns all configuration values.
	 *	@access		public
	 *	@return		array
	 */
	public function getConfigValues()
	{
		return $this->config;
	}
	
	/**
	 *	Reads a configuration.
	 *	@access		protected
	 *	@return		void
	 */
	protected function read()
	{
		if( $this->useCache )
		{
			$fileName	= $this->pathCache.basename( $this->fileName ).".cache";
			if( file_exists( $fileName ) && file_exists( $this->fileName ) && filemtime( $fileName ) == filemtime( $this->pathConfig.$this->fileName ) )
			{
				return $this->readCache( $fileName );
			}
			else
			{
				$this->readWDDX();
				return $this->writeCache( $fileName );
			}
		}
		return $this->readWDDX();
	
	}
	
	/**
	 *	Reads configuration from cache.
	 *	@access		protected
	 *	@param		string		$fileName		URI of configration File
	 *	@return		void
	 */
	protected function readCache( $fileName )
	{
		$file			= new File_Reader( $fileName );
		$content		= $file->readString();
		$this->config	= unserialize( $content );
	}

	/**
	 *	Reads configuration.
	 *	@access		protected
	 *	@return		void
	 */
	protected function readWDDX()
	{
		$wr	= new XML_WDDX_FileReader( $this->pathConfig.$this->fileName );
		$this->config = $wr->read();
	}

	/**
	 *	Sets a configuration value in a section.
	 *	@access		public
	 *	@param		string		$section		Section
	 *	@param		string		$key			Key of configuration
	 *	@param		string		$value			Value of configuration
	 *	@return		string
	 */
	public function setConfigValue( $section, $key, $value )
	{
		$this->config[$section][$key] = $value;
		$this->write();
	}

	/**
	 *	Saves a configuration.
	 *	@access		protected
	 *	@param		string		$fileName		URI of configuration file
	 *	@return		void
	 */
	protected function write()
	{
		$ww		= new XML_WDDX_FileWriter( $this->fileName );
		foreach( $this->getConfigValues() as $sectionName => $sectionData )
			foreach( $sectionData as $key => $value)
				$ww->add( $key, $value );
		$ww->write();
	}

	/**
	 *	Writes configuration to cache.
	 *	@access		protected
	 *	@param		string		$fileName		URI of configration File
	 *	@return		void
	 */
	protected function writeCache( $fileName )
	{
		$file		= new File_Writer( $fileName, 0777 );
		$content	= serialize( $this->getConfigValues() );
		$file->writeString( $content );
		touch( $fileName, filemtime( $this->pathConfig.$this->fileName ) );
	}
}
?>
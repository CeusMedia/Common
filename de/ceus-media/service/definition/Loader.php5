<?php
/**
 *	Loader for Service Defintions in JSON, XML or YAML.
 *	@package		service.definition
 *	@uses			ADT_JSON_Converter
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.3
 */
/**
 *	Loader for Service Defintions in JSON, XML or YAML.
 *	@package		service.definition
 *	@uses			ADT_JSON_Converter
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.3
 */
class Service_Definition_Loader
{
	/**	@protected		array			$sourceTypes		Array of supported Source Types / Definition File Extensions */	
	protected $sourceTypes	= array(
		'JS'	=> "loadServicesFromJson",
		'JSON'	=> "loadServicesFromJson",
		'XML'	=> "loadServicesFromXml",
		'YAML'	=> "loadServicesFromYaml",
	);
		
	/**
	 *	Loads Service Definitions from XML or YAML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	public function loadServices( $fileName, $cacheFile = NULL )
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'Service Definition File "'.$fileName.'" is not existing.' );
		if( $cacheFile && file_exists( $cacheFile ) && filemtime( $fileName ) <= filemtime( $cacheFile ) )
			return $this->services	= unserialize( file_get_contents( $cacheFile ) );

		$info	= pathinfo( $fileName );
		$ext	= strtoupper( $info['extension'] );
		$types	= array_keys( $this->sourceTypes );
		if( !in_array( $ext, $types ) )
			throw new InvalidArgumentException( 'Defintion Source Type "'.$ext.'" is not supported (only '.implode( ", ", $types ).').' );

		$method		= $this->sourceTypes[$ext];
		$services	= $this->$method( $fileName, $cacheFile );
		if( $cacheFile )
			file_put_contents( $cacheFile, serialize( $services ) );
		return $services;
	}
	
	/**
	 *	Loads Service Definitions from XML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServicesFromJson( $fileName, $cacheFile = false )
	{
		import( 'de.ceus-media.adt.json.Converter' );
		$jsonString		= file_get_contents( $fileName );
		return ADT_JSON_Converter::convertToArray( $jsonString );
	}

	/**
	 *	Loads Service Definitions from XML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServicesFromXml( $fileName, $cacheFile = false )
	{
		import( 'de.ceus-media.service.definition.XmlReader' );
		return Service_Definition_XmlReader::load( $fileName );
	}

	/**
	 *	Loads Service Definitions from YAML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServicesFromYaml( $fileName, $cacheFile = NULL )
	{
		import( 'de.ceus-media.file.yaml.Reader' );
		return File_YAML_Reader::load( $fileName );
	}
}
?>
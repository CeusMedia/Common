<?php
/**
 *	Loader for Service Defintions in JSON, XML or YAML.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@package		net.service.definition
 *	@uses			ADT_JSON_Converter
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.3
 */
/**
 *	Loader for Service Defintions in JSON, XML or YAML.
 *	@package		net.service.definition
 *	@uses			ADT_JSON_Converter
 *	@uses			Service_Definition_XmlReader
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			18.06.2007
 *	@version		0.3
 */
class Net_Service_Definition_Loader
{
	/**	@var		array			$sourceTypes		Array of supported Source Types / Definition File Extensions */	
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
		if( $cacheFile && filemtime( $fileName ) <= @filemtime( $cacheFile ) )
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
	 *	@return		void
	 */
	protected function loadServicesFromJson( $fileName )
	{
		import( 'de.ceus-media.adt.json.Converter' );
		$jsonString		= file_get_contents( $fileName );
		$definition		= ADT_JSON_Converter::convertToArray( $jsonString );
		$this->completeDefinition( $definition );
		return $definition;
	}

	/**
	 *	Loads Service Definitions from XML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@return		void
	 */
	protected function loadServicesFromXml( $fileName )
	{
		import( 'de.ceus-media.net.service.definition.XmlReader' );
		return Net_Service_Definition_XmlReader::load( $fileName );
	}

	/**
	 *	Loads Service Definitions from YAML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@return		void
	 */
	protected function loadServicesFromYaml( $fileName )
	{
		import( 'de.ceus-media.file.yaml.Reader' );
		$definition	= File_YAML_Reader::load( $fileName );
		$this->completeDefinition( $definition );
		return $definition;
	}	
	
	protected function completeDefinition( &$definition )
	{
		foreach( array_keys( $definition['services'] ) as $serviceName )
		{
			$service	= $definition['services'][$serviceName];
			if( !isset( $service['parameters'] ) )
				$definition['services'][$serviceName]['parameters']	= array();
			if( !isset( $service['roles'] ) )
				$definition['services'][$serviceName]['roles']	= array();
			if( !isset( $service['status'] ) )
				$definition['services'][$serviceName]['status']	= NULL;
		}
	}
}
?>
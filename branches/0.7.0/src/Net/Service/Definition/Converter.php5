<?php
/**
 *	Converts Service Definitions between JSON, XML and YAML.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		net.service.definition
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.net.service.definition.XmlReader' );
import( 'de.ceus-media.net.service.definition.XmlWriter' );
import( 'de.ceus-media.file.yaml.Reader' );
import( 'de.ceus-media.file.yaml.Writer' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.adt.json.Converter' );
import( 'de.ceus-media.adt.json.Formater' );
/**
 *	Converts Service Definitions between JSON, XML and YAML.
 *	@category		cmClasses
 *	@package		net.service.definition
 *	@uses			Net_Serivce_Definition_Reader
 *	@uses			Net_Service_Definition_Writer
 *	@uses			File_YAML_Reader
 *	@uses			File_YAML_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			ADT_JSON_Converter
 *	@uses			ADT_JSON_Formater
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 *	@todo			Code Doc
 */
class Net_Service_Definition_Converter
{
	/**
	 *	Converts a JSON File into a XML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$jsonFile		URI of JSON File to read
	 *	@param		string		$xmlFile		URI of XML File to write
	 *	@return		void
	 */
	public static function convertJsonFileToXmlFile( $jsonFile, $xmlFile )
	{
		$json	= File_Reader::load( $jsonFile );
		$data	= ADT_JSON_Converter::convertToArray( $json );
		return Net_Service_Definition_XmlWriter::save( $xmlFile, $data );
	}

	/**
	 *	Converts a JSON File into a YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$jsonFile		URI of JSON File to read
	 *	@param		string		$yamlFile		URI of YAML File to write
	 *	@return		void
	 */
	public static function convertJsonFileToYamlFile( $jsonFile, $yamlFile )
	{
		$json	= File_Reader::load( $jsonFile );
		$data	= ADT_JSON_Converter::convertToArray( $json );
		return File_YAML_Writer::save( $yamlFile, $data );
	}

	/**
	 *	Converts a XML File into a YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$xmlFile		URI of XML File to read
	 *	@param		string		$jsonFile		URI of JSON File to write
	 *	@return		void
	 */
	public static function convertXmlFileToJsonFile( $xmlFile, $jsonFile )
	{
		$data	= Net_Service_Definition_XmlReader::load( $xmlFile );
		self::reduceDefinition( $data );
		$json	= json_encode( $data );
		$json	= ADT_JSON_Formater::format( $json );
		return File_Writer::save( $jsonFile, $json );
	}

	
	/**
	 *	Converts a XML File into a YAML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$xmlFile		URI of XML File to read
	 *	@param		string		$yamlFile		URI of YAML File to write
	 *	@return		void
	 */
	public static function convertXmlFileToYamlFile( $xmlFile, $yamlFile )
	{
		$data	= Net_Service_Definition_XmlReader::load( $xmlFile );
		self::reduceDefinition( $data );
		return File_YAML_Writer::save( $yamlFile, $data );
	}

	/**
	 *	Converts a YAML File into a JSON File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$yamlFile		URI of YAML File to read
	 *	@param		string		$jsonFile		URI of JSON File to write
	 *	@return		void
	 */
	public static function convertYamlFileToJsonFile( $yamlFile, $jsonFile )
	{
		$data	= File_YAML_Reader::load( $yamlFile );
		self::reduceDefinition( $data );
		$json	= json_encode( $data );
		$json	= ADT_JSON_Formater::format( $json );
		return File_Writer::save( $jsonFile, $json );
	}
	
	/**
	 *	Converts a YAML File into a XML File statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$yamlFile		URI of YAML File to read
	 *	@param		string		$xmlFile		URI of XML File to write
	 *	@return		void
	 */
	public static function convertYamlFileToXmlFile( $yamlFile, $xmlFile )
	{
		$data	= File_YAML_Reader::load( $yamlFile );
		self::reduceDefinition( $data );
		return Net_Service_Definition_XmlWriter::save( $xmlFile, $data );
	}

	protected static function reduceDefinition( &$definition )
	{
		if( empty( $definition['filters'] ) )
			unset( $definition['filters'] );
		foreach( array_keys( $definition['services'] ) as $serviceName )
		{
			$service	=& $definition['services'][$serviceName];
			if( empty( $service['parameters'] ) )
				unset( $definition['services'][$serviceName]['parameters'] );
			else
				foreach( $service['parameters'] as $parameterName => $parameterData )
				{
					$parameter	=& $service['parameters'][$parameterName];
					foreach( $parameterData as $parameterDataKey => $parameterDataValue )
						if( is_null( $parameter[$parameterDataKey] ) || !count( $parameter[$parameterDataKey] ) )
							unset( $parameter[$parameterDataKey] );
				}
			if( empty( $service['roles'] ) )
				unset( $definition['services'][$serviceName]['roles'] );
			if( empty( $service['filters'] ) )
				unset( $definition['services'][$serviceName]['filters'] );
			if( !( isset( $service['status'] ) && strlen( $service['status'] ) ) )
				unset( $definition['services'][$serviceName]['status'] );
		}
	}
}
?>
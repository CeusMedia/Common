<?php
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
 *	@package		net.service.definition
 *	@uses			Net_Serivce_Definition_Reader
 *	@uses			Net_Service_Definition_Writer
 *	@uses			File_YAML_Writer
 *	@uses			File_YAML_Reader
 *	@uses			File_YAML_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			ADT_JSON_Converter
 *	@uses			ADT_JSON_Formater
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Converts Service Definitions between JSON, XML and YAML.
 *	@package		net.service.definition
 *	@uses			Net_Serivce_Definition_Reader
 *	@uses			Net_Service_Definition_Writer
 *	@uses			File_YAML_Reader
 *	@uses			File_YAML_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@uses			ADT_JSON_Converter
 *	@uses			ADT_JSON_Formater
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_Service_Definition_Converter
{
	/**
	 *	Converts a JSON File into a XML File statically.
	 *	@access		public
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
	 *	@param		string		$xmlFile		URI of XML File to read
	 *	@param		string		$jsonFile		URI of JSON File to write
	 *	@return		void
	 */
	public static function convertXmlFileToJsonFile( $xmlFile, $jsonFile )
	{
		$data	= Net_Service_Definition_XmlReader::load( $xmlFile );
		$json	= json_encode( $data );
		$json	= ADT_JSON_Formater::format( $json );
		return File_Writer::save( $jsonFile, $json );
	}
	
	/**
	 *	Converts a XML File into a YAML File statically.
	 *	@access		public
	 *	@param		string		$xmlFile		URI of XML File to read
	 *	@param		string		$yamlFile		URI of YAML File to write
	 *	@return		void
	 */
	public static function convertXmlFileToYamlFile( $xmlFile, $yamlFile )
	{
		$data	= Net_Service_Definition_XmlReader::load( $xmlFile );
		return File_YAML_Writer::save( $yamlFile, $data );
	}

	/**
	 *	Converts a YAML File into a JSON File statically.
	 *	@access		public
	 *	@param		string		$yamlFile		URI of YAML File to read
	 *	@param		string		$jsonFile		URI of JSON File to write
	 *	@return		void
	 */
	public static function convertYamlFileToJsonFile( $yamlFile, $jsonFile )
	{
		$data	= File_YAML_Reader::load( $yamlFile );
		$json	= json_encode( $data );
		$json	= ADT_JSON_Formater::format( $json );
		return File_Writer::save( $jsonFile, $json );
	}
	
	/**
	 *	Converts a YAML File into a XML File statically.
	 *	@access		public
	 *	@param		string		$yamlFile		URI of YAML File to read
	 *	@param		string		$xmlFile		URI of XML File to write
	 *	@return		void
	 */
	public static function convertYamlFileToXmlFile( $yamlFile, $xmlFile )
	{
		$data	= File_YAML_Reader::load( $yamlFile );
		return Net_Service_Definition_XmlWriter::save( $xmlFile, $data );
	}
}
?>
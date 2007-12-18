<?php
import( 'de.ceus-media.service.XmlDefinition' );
import( 'de.ceus-media.file.yaml.Reader' );
import( 'de.ceus-media.file.yaml.Writer' );
import( 'de.ceus-media.file.Reader' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Converts Service Definitions between XML and YAML.
 *	@package		service
 *	@uses			Service_XmlDefinition
 *	@uses			File_YAML_Reader
 *	@uses			File_YAML_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Converts Service Definitions between XML and YAML.
 *	@package		service
 *	@uses			Service_XmlDefinition
 *	@uses			File_YAML_Reader
 *	@uses			File_YAML_Writer
 *	@uses			File_Reader
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Service_DefinitionConverter
{
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
		$xml	= Service_XmlDefinition::build( $data );
		return File_Writer::save( $xmlFile, $xml );
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
		$xml	= File_Reader::load( $xmlFile );
		$data	= Service_XmlDefinition::parse( $xml );
		return File_YAML_Writer::save( $yamlFile, $data );
	}
}
?>
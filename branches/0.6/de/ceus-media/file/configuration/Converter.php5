<?php
import( 'de.ceus-media.file.Writer' );
import( 'de.ceus-media.file.ini.Creator' );
import( 'de.ceus-media.file.ini.Reader' );
import( 'de.ceus-media.adt.json.Formater' );
import( 'de.ceus-media.adt.json.Converter' );
import( 'de.ceus-media.xml.ElementReader' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.FileWriter' );
/**
 *	Converter for Configuration to translate between INI, JSON and XML.
 *	YAML  will be supported if Spyc is improved.
 *	@package		file.configuration
 *	@uses			File_Writer
 *	@uses			File_INI_Creator
 *	@uses			File_INI_Reader
 *	@uses			ADT_JSON_Converter
 *	@uses			ADT_JSON_Formater
 *	@uses			XML_ElementReader
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			06.05.2008
 *	@version		0.1
 */
/**
 *	Converter for Configuration to translate between INI, JSON and XML.
 *	YAML will be supported if Spyc is improved.
 *	@package		file.configuration
 *	@uses			File_Writer
 *	@uses			File_INI_Creator
 *	@uses			File_INI_Reader
 *	@uses			ADT_JSON_Converter
 *	@uses			ADT_JSON_Formater
 *	@uses			XML_ElementReader
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_FileWriter
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			06.05.2008
 *	@version		0.1
 */
class File_Configuration_Converter
{
	/**	@var		string		$iniTypePattern		Pattern for Types in INI Comments */
	public static $iniTypePattern = '@^(string|integer|int|double|boolean|bool)[ \t]*[:-]*[ \t]*(.*)$@';

	/**
	 *	Converts Configuration File from INI to JSON and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertIniToJson( $sourceFile, $targetFile )
	{
		$data	= self::loadIni( $sourceFile );
		return self::saveJson( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from INI to XML and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertIniToXml( $sourceFile, $targetFile )
	{
		$data	= self::loadIni( $sourceFile );
		return self::saveXml( $targetFile, $data );
	}
	
	/**
	 *	Converts Configuration File from JSON to INI and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertJsonToIni( $sourceFile, $targetFile )
	{
		$data	= self::loadJson( $sourceFile );
		return self::saveIni( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from JSON to XML and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertJsonToXml( $sourceFile, $targetFile )
	{
		$data	= self::loadJson( $sourceFile );
		return self::saveXml( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from XML to INI and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertXmlToIni( $sourceFile, $targetFile )
	{
		$data		= self::loadXml( $sourceFile );
		return self::saveIni( $targetFile, $data );
	}
	
	/**
	 *	Converts Configuration File from XML to JSON and returns Length of Target File.
	 *	@access		public
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertXmlToJson( $sourceFile, $targetFile )
	{
		$data	= self::loadXml( $sourceFile );
		return self::saveJson( $targetFile, $data );
	}

	/**
	 *	Loads Configuration Data from INI File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of INI File.
	 *	@return		array
	 */
	protected static function loadIni( $fileName )
	{
		$reader	= new File_INI_Reader( $fileName, TRUE );
		$ini	= $reader->getCommentedProperties();
		foreach( $ini as $sectionName => $sectionData )
		{
			foreach( $sectionData as $pair )
			{
				$item	= array(
					'key'		=> $pair['key'],
					'value'		=> $pair['value'],
					'type'		=> "string",
				);
				if( isset( $pair['comment'] ) )
				{
					$matches	= array();
					if( preg_match_all( self::$iniTypePattern, $pair['comment'], $matches ) )
					{
						$item['type']		= $matches[1][0];
						if( $matches[2][0] )
							$item['comment']	= $matches[2][0];
						settype( $item['value'], $item['type'] );
					}
					else
						$item['comment']	= $pair['comment'];
				}
				$data[$sectionName][]	= $item;
			}
		}
		return $data;
	}

	/**
	 *	Loads Configuration Data from JSON File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of JSON File.
	 *	@return		array
	 */
	protected static function loadJson( $fileName )
	{
		$json	= File_Reader::load( $fileName );
		$json	= ADT_JSON_Converter::convertToArray( $json );
		foreach( $json as $sectionName => $sectionData )
		{
			foreach( $sectionData as $pairKey => $pairData )
			{
				$pairData	= array_merge( array( 'key' => $pairKey ), $pairData );
				$data[$sectionName][]	= $pairData;
			}
		}
		return $data;
	}

	/**
	 *	Loads Configuration Data from XML File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of XML File.
	 *	@return		array
	 */
	protected static function loadXml( $fileName )
	{
		$xml	= XML_ElementReader::readFile( $fileName );
		foreach( $xml as $sectionNode )
		{
			$sectionName	= $sectionNode->getAttribute( 'name' );
			foreach( $sectionNode as $valueNode )
			{
				$item	= array(
					'key'		=> $valueNode->getAttribute( 'name' ),
					'value'		=> (string) $valueNode,
					'type'		=> $valueNode->getAttribute( 'type' ),
				);
	
				if( $valueNode->hasAttribute( 'comment' ) )
					$item['comment']	= $valueNode->getAttribute( 'comment' );
				settype( $item['value'], $item['type'] );
				$data[$sectionName][]	= $item;
			}
		}
		return $data;
	}
	
	/**
	 *	Saves Configuration Data as INI File and returns Number of written Bytes.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of INI File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 */
	protected static function saveIni( $fileName, $data )
	{
		$creator	= new File_INI_Creator( TRUE );
		foreach( $data as $sectionName => $sectionData )
		{
			$creator->addSection( $sectionName );
			foreach( $sectionData as $pair )
			{
				switch( $pair['type'] )
				{
					case 'string':
						$pair['value']	= '"'.addslashes( $pair['value'] ).'"';
						break;
					case 'bool':
					case 'boolean':
						$pair['value']	= $pair['value'] ? "yes" : "no";
						break;
				}
				$pair['comment']	= $pair['comment'] ? $pair['type'].": ".$pair['comment'] : $pair['type'];
				$creator->addProperty( $pair['key'], $pair['value'], $pair['comment'] );
			}
		}
		return $creator->write( $fileName );
	}

	/**
	 *	Saves Configuration Data as JSON File and returns Number of written Bytes.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of JSON File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 */
	protected static function saveJson( $fileName, $data )
	{
		$json	= array();
		foreach( $data as $sectionName => $sectionData )
		{
			foreach( $sectionData as $pair )
			{
				$key	= $pair['key'];
				unset( $pair['key'] );
				$json[$sectionName][$key]	= $pair;
			}
		}
		$json	= ADT_JSON_Formater::format( $json, TRUE );
		return File_Writer::save( $fileName, $json );
	}

	/**
	 *	Saves Configuration Data as XML File and returns Number of written Bytes.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of XML File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 */
	protected static function saveXml( $fileName, $data )
	{
		$root	= new XML_DOM_Node( "configuration" );
		foreach( $data as $sectionName => $sectionData )
		{
			$sectionNode	=& new XML_DOM_Node( "section" );
			$sectionNode->setAttribute( 'name', $sectionName );			
			foreach( $sectionData as $pair )
			{
				$comment	= isset( $pair['comment'] ) ? $pair['comment'] : NULL;
				$valueNode	=& new XML_DOM_Node( "value", $pair['value'] );
				$valueNode->setAttribute( 'type', $pair['type'] );
				$valueNode->setAttribute( 'name', $pair['key'] );
				if( $pair['comment'] )
					$valueNode->setAttribute( 'comment', $comment );
				$sectionNode->addChild( $valueNode );
			}
			$root->addChild( $sectionNode );
		}
		return XML_DOM_FileWriter::save( $fileName, $root );
	}
}
?>
<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Converter for Configuration to translate between INI, JSON and XML.
 *	YAML  will be supported if Spyc is improved.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Configuration;

use CeusMedia\Common\ADT\JSON\Builder as JsonBuilder;
use CeusMedia\Common\ADT\JSON\Converter as JsonConverter;
use CeusMedia\Common\ADT\JSON\Pretty as JsonPretty;
use CeusMedia\Common\FS\File\INI\Creator as IniFileCreator;
use CeusMedia\Common\FS\File\INI\Reader as IniFileReader;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use CeusMedia\Common\XML\DOM\FileWriter as XmlFileWriter;
use CeusMedia\Common\XML\DOM\Node;
use CeusMedia\Common\XML\ElementReader as XmlElementReader;
use CeusMedia\Common\XML\Element as XmlElement;
use DOMException;
use Exception;

/**
 *	Converter for Configuration to translate between INI, JSON and XML.
 *	YAML will be supported if Spyc is improved.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Converter
{
	/**	@var		string		$iniTypePattern		Pattern for Types in INI Comments */
	public static $iniTypePattern = '@^(string|integer|int|double|boolean|bool)[ \t]*[:-]*[ \t]*(.*)$@';

	/**
	 *	Converts Configuration File from INI to JSON and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertIniToJson( string $sourceFile, string $targetFile ): int
	{
		$data	= self::loadIni( $sourceFile );
		return self::saveJson( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from INI to XML and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 *	@throws		DOMException
	 */
	public static function convertIniToXml( string $sourceFile, string $targetFile ): int
	{
		$data	= self::loadIni( $sourceFile );
		return self::saveXml( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from JSON to INI and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 */
	public static function convertJsonToIni( string $sourceFile, string $targetFile ): int
	{
		$data	= self::loadJson( $sourceFile );
		return self::saveIni( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from JSON to XML and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 *	@throws		DOMException
	 */
	public static function convertJsonToXml( string $sourceFile, string $targetFile ): int
	{
		$data	= self::loadJson( $sourceFile );
		return self::saveXml( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from XML to INI and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 *	@throws		Exception
	 */
	public static function convertXmlToIni( string $sourceFile, string $targetFile ): int
	{
		$data		= self::loadXml( $sourceFile );
		return self::saveIni( $targetFile, $data );
	}

	/**
	 *	Converts Configuration File from XML to JSON and returns Length of Target File.
	 *	@access		public
	 *	@static
	 *	@param		string		$sourceFile			File Name of Source File
	 *	@param		string		$targetFile			File Name of Target File
	 *	@return		int
	 *	@throws		Exception
	 */
	public static function convertXmlToJson( string $sourceFile, string $targetFile ): int
	{
		$data	= self::loadXml( $sourceFile );
		return self::saveJson( $targetFile, $data );
	}

	/**
	 *	Loads Configuration Data from INI File.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name of INI File.
	 *	@return		array
	 */
	protected static function loadIni( string $fileName ): array
	{
		$data	= [];
		$reader	= new IniFileReader( $fileName, TRUE );
		$ini	= $reader->getCommentedProperties();
		foreach( $ini as $sectionName => $sectionData ){
			foreach( $sectionData as $pair ){
				$item	= [
					'key'		=> $pair['key'],
					'value'		=> $pair['value'],
					'type'		=> "string",
				];
				if( isset( $pair['comment'] ) ){
					$matches	= [];
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
	 *	@static
	 *	@param		string		$fileName		File Name of JSON File.
	 *	@return		array
	 */
	protected static function loadJson( string $fileName ): array
	{
		$data	= [];
		$json	= FileReader::load( $fileName );
		$json	= JsonConverter::convertToArray( $json );
		foreach( $json as $sectionName => $sectionData ){
			foreach( $sectionData as $pairKey => $pairData ){
				$pairData	= array_merge( ['key' => $pairKey], $pairData );
				$data[$sectionName][]	= $pairData;
			}
		}
		return $data;
	}

	/**
	 *	Loads Configuration Data from XML File.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name of XML File.
	 *	@return		array
	 *	@throws		Exception
	 */
	protected static function loadXml( string $fileName ): array
	{
		$data	= [];
		$xml	= XmlElementReader::readFile( $fileName );
		/** @var XmlElement $sectionNode */
		foreach( $xml as $sectionNode ){
			$sectionName	= $sectionNode->getAttribute( 'name' );
			foreach( $sectionNode as $valueNode ){
				$item	= [
					'key'		=> $valueNode->getAttribute( 'name' ),
					'value'		=> (string) $valueNode,
					'type'		=> $valueNode->getAttribute( 'type' ),
				];

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
	 *	@static
	 *	@param		string		$fileName		File Name of INI File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 */
	protected static function saveIni( string $fileName, array $data ): int
	{
		$creator	= new IniFileCreator( TRUE );
		foreach( $data as $sectionName => $sectionData ){
			$creator->addSection( $sectionName );
			foreach( $sectionData as $pair ){
				switch( $pair['type'] ){
					case 'string':
						$pair['value']	= '"'.addslashes( $pair['value'] ).'"';
						break;
					case 'bool':
					case 'boolean':
						$pair['value']	= $pair['value'] ? "yes" : "no";
						break;
				}
				$pair['comment']	= isset( $pair['comment'] ) ? $pair['type'].": ".$pair['comment'] : $pair['type'];
				$creator->addProperty( $pair['key'], $pair['value'], $pair['comment'] );
			}
		}
		return $creator->write( $fileName );
	}

	/**
	 *	Saves Configuration Data as JSON File and returns Number of written Bytes.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name of JSON File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 */
	protected static function saveJson( string $fileName, array $data ): int
	{
		$json	= [];
		foreach( $data as $sectionName => $sectionData ){
			foreach( $sectionData as $pair ){
				$key	= $pair['key'];
				unset( $pair['key'] );
				$json[$sectionName][$key]	= $pair;
			}
		}
//		$json	= json_encode( $json, JSON_PRETTY_PRINT );
		$json	= JsonPretty::print( JsonBuilder::encode( $json ), TRUE );
		return FileWriter::save( $fileName, $json );
	}

	/**
	 *	Saves Configuration Data as XML File and returns Number of written Bytes.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName		File Name of XML File
	 *	@param		array		$data			Configuration Data as Array
	 *	@return		int
	 *	@throws		DOMException
	 */
	protected static function saveXml( string $fileName, array $data ): int
	{
		$root	= new Node( "configuration" );
		foreach( $data as $sectionName => $sectionData ){
			$sectionNode	= new Node( "section" );
			$sectionNode->setAttribute( 'name', $sectionName );
			foreach( $sectionData as $pair ){
				$comment	= $pair['comment'] ?? NULL;
				$valueNode	= new Node( "value", $pair['value'] );
				$valueNode->setAttribute( 'type', $pair['type'] );
				$valueNode->setAttribute( 'name', $pair['key'] );
				if( isset( $pair['comment'] ) )
					$valueNode->setAttribute( 'comment', $comment );
				$sectionNode->addChild( $valueNode );
			}
			$root->addChild( $sectionNode );
		}
		return XmlFileWriter::save( $fileName, $root );
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Configuration Files of different Types.
 *	Supported File Types are CONF, INI, JSON, YAML and XML.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\FS\File\Configuration;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\ADT\JSON\Converter as JsonConverter;
use CeusMedia\Common\FS\File\INI\Reader as IniFileReader;
use CeusMedia\Common\FS\File\Reader as FileReader;
use CeusMedia\Common\FS\File\Writer as FileWriter;
use CeusMedia\Common\FS\File\YAML\Reader as YamlFileReader;
use CeusMedia\Common\XML\Element as XmlElement;
use CeusMedia\Common\XML\ElementReader as XmlElementReader;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Reader for Configuration Files of different Types.
 *	Supported File Types are CONF, INI, JSON, YAML and XML.
 *	@category		Library
 *	@package		CeusMedia_Common_FS_File_Configuration
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader extends Dictionary
{
	/**	@var		bool				$iniQuickLoad	Flag: load INI Files with parse_ini_files, no Type Support */
	public static bool $iniQuickLoad	= FALSE;

	/** @var		string				$source */
	protected string $source;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$fileName		File Name of Configuration File
	 *	@param		string|NULL		$cachePath		Path to Cache File
	 *	@return		void
	 *	@throws		Exception
	 *	@throws		InvalidArgumentException
	 */
	public function __construct( string $fileName, ?String $cachePath = NULL )
	{
		parent::__construct();
		$this->source	= $this->loadFile( $fileName, $cachePath );
	}

	/**
	 *	Return a Value or Pair Map of Dictionary by its Key.
	 *	This Method overwrites ALG_List_LevelMap::get for Performance Boost.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@param		mixed		$default	Value to return if key is not set, default: NULL
	 *	@return		mixed
	 */
	public function get( string $key, $default = NULL )
	{
		//  no Key given
		if( empty( $key ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Key is set on its own
		if( isset( $this->pairs[$key] ) )
			//  return Value
			return $this->pairs[$key];
		//  Key has not been found
		else{
			//  prepare Prefix Key to search for
			$key		.= ".";
			//  define empty Map
			$list		= [];
			//  get Length of Prefix Key outside the Loop
			$length		= strlen( $key );
			//  iterate all stores Pairs
			foreach( $this->pairs as $pairKey => $pairValue ){
				//  pre-check for Performance
				if( $pairKey[0] !== $key[0] ){
					//  Pairs with Prefix Keys are passed
					if( count( $list ) )
						//  break Loop -> big Performance Boost
						return $list;
					//  skip Pair
					continue;
				}
				//  Prefix Key is found
				if( strpos( $pairKey, $key ) === 0 )
					//  collect Pair
					$list[substr( $pairKey, $length )]	= $pairValue;
			}
			//  found Pairs
			if( count( $list ) )
				//  return Pair Map
				return $list;
		}
		//  nothing given default, default: NULL
		return $default;
	}

	/**
	 *	Loads Configuration File directly or from Cache and returns Source (cache|ini|conf|xml|...).
	 *	@access		protected
	 *	@param		string			$fileName		File Name of Configuration File
	 *	@param		string|NULL		$cachePath		Path to Cache File
	 *	@return		string
	 *	@throws		Exception
	 *	@throws		InvalidArgumentException
	 *	@throws		RuntimeException
	 */
	protected function loadFile( string $fileName, ?string $cachePath = NULL ): string
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'Configuration File "'.$fileName.'" is not existing.' );

		if( is_string( $cachePath ) ){
			$cachePath	= preg_replace( "@([^/])$@", "\\1/", $cachePath );
			$cacheFile	= $cachePath.basename( $fileName ).".cache";
			if( $this->tryToLoadFromCache( $cacheFile, filemtime( $fileName ) ) )
				return "cache";
		}

		$info	= pathinfo( $fileName );
		switch( $info['extension'] ){
			case 'ini':
			case 'conf':
				$this->loadIniFile( $fileName );
				break;
			case 'js':
			case 'json':
				$this->loadJsonFile( $fileName );
				break;
			case 'yml':
			case 'yaml':
				$this->loadYamlFile( $fileName );
				break;
			case 'xml':
				$this->loadXmlFile( $fileName );
				break;
			default:
				throw new InvalidArgumentException( 'File Type "'.$info['extension'].'" is not supported.' );
		}
		ksort( $this->pairs );
		if( is_string( $cachePath ) && isset( $cacheFile ) )
			FileWriter::save( $cacheFile, serialize( $this->pairs ), 0640 );
		return $info['extension'];
	}

	/**
	 *	Loads Configuration from INI File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadIniFile( string $fileName )
	{
		if( self::$iniQuickLoad ){
			$array	= parse_ini_file( $fileName, TRUE );
			foreach( $array as $sectionName => $sectionData )
				foreach( $sectionData as $key => $value )
					$this->pairs[$sectionName.".".$key]	= $value;
		}
		else{
			$pattern	= '@^(string|integer|int|double|boolean|bool).*$@';
			$reader		= new IniFileReader( $fileName, TRUE );
			$comments	= $reader->getComments();
			foreach( $reader->getProperties() as $sectionName => $sectionData ){
				foreach( $sectionData as $key => $value ){
					if( isset( $comments[$sectionName][$key] ) ){
						$matches	= [];
						if( preg_match_all( $pattern, $comments[$sectionName][$key], $matches ) ){
							$type		= $matches[1][0];
							settype( $value, $type );
						}
					}
					$this->pairs[$sectionName.".".$key]	= $value;
				}
			}
		}
	}

	/**
	 *	Loads Configuration from JSON File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadJsonFile( string $fileName )
	{
		$json	= FileReader::load( $fileName );
		$array	= JsonConverter::convertToArray( $json );
		foreach( $array as $sectionName => $sectionData )
			foreach( $sectionData as $key => $item )
				$this->pairs[$sectionName.".".$key]	= $item['value'];
	}

	/**
	 *	Loads Configuration from XML File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 *	@throws		Exception
	 */
	protected function loadXmlFile( string $fileName )
	{
		//  get root element of XML file
		$root	= XmlElementReader::readFile( $fileName );
		$this->pairs	= [];
		//  iterate sections
		/** @var XmlElement $sectionNode */
		foreach( $root as $sectionNode ){
			//  get section name
			$sectionName	= $sectionNode->getAttribute( 'name' );
			//  read section
			$this->loadXmlSection( $sectionNode, $sectionName );
		}
		//  sort resulting pairs by key
		ksort( $this->pairs );
	}

	/**
	 *	Reads sections and values of an XML file node, recursively, and stores pairs in-situ.
	 *	@access		protected
	 *	@param		XmlElement		$node			Section XML node to read
	 *	@param		string|NULL		$path			Path to this section
	 *	@return		void
	 */
	protected function loadXmlSection( XmlElement $node, ?string $path = NULL )
	{
		//  extend path by delimiter
		$path	.= $path ? '.' : '';
		//  iterate node children
		foreach( $node as $child ){
			//  get node name of child
			$name	= $child->getAttribute( 'name' );
			//  dispatch on node name
			switch( $child->getName() ){
				//  section node
				case 'section':
					//  load child section
					$this->loadXmlSection( $child, $path.$name );
					break;
				//  pair node
				case 'value':
					//  default type: string
					$type	= 'string';
					//  type attribute is set
					if( $child->hasAttribute( 'type' ) )
						//  realize type attribute
						$type	= $child->getAttribute( 'type' );
					//  convert node content to value string
					$value	= (string) $child;
					//  apply type to value
					settype( $value, $type );
					//  register pair
					$this->pairs[$path.$name]	= $value;
					break;
			}
		}
	}

	/**
	 *	Loads Configuration from YAML File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadYamlFile( string $fileName )
	{
		$array	= YamlFileReader::load( $fileName );
		foreach( $array as $sectionName => $sectionData )
			foreach( $sectionData as $key => $value )
				$this->pairs[$sectionName.".".$key]	= $value;
	}

	public function remove( string $key ): bool
	{
		//  no Key given
		if( empty( $key ) )
			//  throw Exception
			throw new InvalidArgumentException( 'Key must not be empty.' );
		//  Key is set on its own
		if( isset( $this->pairs[$key] ) ){
			//  remove Pair
			unset( $this->pairs[$key] );
			//  return Success
			return TRUE;
		}

		$count	= 0;
		//  prepare Prefix Key to search for
		$key		.= ".";
		//  iterate all stores Pairs
		foreach( $this->pairs as $pairKey => $pairValue ){
			//  pre-check for Performance
			if( $pairKey[0] !== $key[0] ){
				//  Pairs with Prefix Keys are passed
				if( $count )
					//  break Loop -> big Performance Boost
					break;
				//  skip Pair
				continue;
			}
			//  Prefix Key is found
			if( strpos( $pairKey, $key ) === 0 ){
				//  remove Pair
				unset( $this->pairs[$pairKey] );
				//  count removed Pairs
				$count++;
			}
		}
		//  return number of removed pairs
		return $count > 0;
	}

	/**
	 *	Generates Cache File Name and tries to load Configuration from Cache File.
	 *	@access		protected
	 *	@param		string		$cacheFile		File Name of Cache File
	 *	@param		int			$lastChange		Last Change of Configuration File
	 *	@return		bool
	 */
	protected function tryToLoadFromCache( string $cacheFile, int $lastChange ): bool
	{
		if( !file_exists( $cacheFile ) )
			return FALSE;

		$lastCache	= @filemtime( $cacheFile );
		if( $lastCache && $lastChange <= $lastCache ){
			$content	= file_get_contents( $cacheFile );
			$array		= @unserialize( $content );
			if( is_array( $array ) ){
				$this->pairs	= $array;
				return TRUE;
			}
		}
		return FALSE;
	}
}

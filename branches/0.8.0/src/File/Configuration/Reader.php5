<?php
/**
 *	Reader for Configuration Files of different Types.
 *	Supported File Types are CONF, INI, JSON, YAML and XML.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		File.Configuration
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			06.05.2008
 *	@version		$Id$
 */
/**
 *	Reader for Configuration Files of different Types.
 *	Supported File Types are CONF, INI, JSON, YAML and XML.
 *	@category		cmClasses
 *	@package		File.Configuration
 *	@extends		ADT_List_LevelMap
 *	@uses			ADT_JSON_Converter
 *	@uses			File_Writer
 *	@uses			File_INI_Reader
 *	@uses			File_YAML_Reader
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			06.05.2008
 *	@version		$Id$
 */
#class File_Configuration_Reader extends ADT_List_LevelMap
class File_Configuration_Reader extends ADT_List_Dictionary
{
	/**	@var		bool		$iniQuickLoad	Flag: load INI Files with parse_ini_files, no Type Support */
	public static $iniQuickLoad		= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@param		string		$cachePath		Path to Cache File
	 *	@return		void
	 */
	public function __construct( $fileName, $cachePath = NULL )
	{
		$this->source	= $this->loadFile( $fileName, $cachePath );
	}
	
	/**
	 *	Return a Value or Pair Map of Dictionary by its Key.
	 *	This Method overwrites ALG_List_LevelMap::get for Performance Boost.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		mixed
	 */
	public function get( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
			return $this->pairs[$key];															//  return Value
		else																					//  Key has not been found
		{
			$key		.= ".";																	//  prepare Prefix Key to seach for
			$list		= array();																//  define empty Map
			$length		= strlen( $key );														//  get Length of Prefix Key outside the Loop
			foreach( $this->pairs as $pairKey => $pairValue )									//  iterate all stores Pairs
			{
				if( $pairKey[0] !== $key[0] )													//  precheck for Performance
				{
					if( count( $list ) )														//  Pairs with Prefix Keys are passed
						return $list;															//  break Loop -> big Performance Boost
					continue;																	//  skip Pair
				}
				if( strpos( $pairKey, $key ) === 0 )											//  Prefix Key is found
					$list[substr( $pairKey, $length )]	= $pairValue;							//  collect Pair
			}
			if( count( $list ) )																//  found Pairs
				return $list;																	//  return Pair Map
		}
		return NULL;																			//  nothing found
	}
	
	public function remove( $key )
	{
		if( empty( $key ) )																		//  no Key given
			throw new InvalidArgumentException( 'Key must not be empty.' );						//  throw Exception
		if( isset( $this->pairs[$key] ) )														//  Key is set on its own
		{
			unset( $this->pairs[$key] );														//  remove Pair
			return 1;																			//  return Success
		}

		$count	= 0;
		$key		.= ".";																		//  prepare Prefix Key to seach for
		$length		= strlen( $key );															//  get Length of Prefix Key outside the Loop
		foreach( $this->pairs as $pairKey => $pairValue )										//  iterate all stores Pairs
		{
			if( $pairKey[0] !== $key[0] )														//  precheck for Performance
			{
				if( $count )																	//  Pairs with Prefix Keys are passed
					break;																		//  break Loop -> big Performance Boost
				continue;																		//  skip Pair
			}
			if( strpos( $pairKey, $key ) === 0 )												//  Prefix Key is found
			{
				unset( $this->pairs[$pairKey] );												//  remove Pair
				$count++;																		//  count removed Pairs
			}
		}
		return $count;																			//  return number of removed pairs
	}

	/**
	 *	Loads Configuration File directly or from Cache and returns Source (cache|ini|conf|xml|...).
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@param		string		$cachePath		Path to Cache File
	 *	@return		string
	 */
	protected function loadFile( $fileName, $cachePath = NULL )
	{
		if( !file_exists( $fileName ) )
			throw new RuntimeException( 'Configuration File "'.$fileName.'" is not existing.' );

		if( is_string( $cachePath ) )
		{
			$cachePath	= preg_replace( "@([^/])$@", "\\1/", $cachePath );
			$cacheFile	= $cachePath.basename( $fileName ).".cache";
			if( $this->tryToLoadFromCache( $cacheFile, filemtime( $fileName ) ) )
				return "cache";
		}
		
		$info	= pathinfo( $fileName );
		switch( $info['extension'] )
		{
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
		if( !empty( $cachePath ) )
		{
			File_Writer::save( $cacheFile, serialize( $this->pairs ), 0640 );
		}
		return $info['extension'];
	}

	/**
	 *	Loads Configuration from INI File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadIniFile( $fileName )
	{
		if( self::$iniQuickLoad )
		{
			$array	= parse_ini_file( $fileName, TRUE );
			foreach( $array as $sectionName => $sectionData )
				foreach( $sectionData as $key => $value )
					$this->pairs[$sectionName.".".$key]	= $value;
		}
		else
		{
			$pattern	= '@^(string|integer|int|double|boolean|bool).*$@';
			$reader		= new File_INI_Reader( $fileName, TRUE );
			$comments	= $reader->getComments();
			foreach( $reader->getProperties() as $sectionName => $sectionData )
			{
				foreach( $sectionData as $key => $value )
				{
					if( isset( $comments[$sectionName][$key] ) )
					{
						$matches	= array();
						if( preg_match_all( $pattern, $comments[$sectionName][$key], $matches ) )
						{
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
	protected function loadJsonFile( $fileName )
	{
		$json	= File_Reader::load( $fileName );
		$array	= ADT_JSON_Converter::convertToArray( $json );
		foreach( $array as $sectionName => $sectionData )
			foreach( $sectionData as $key => $item )
				$this->pairs[$sectionName.".".$key]	= $item['value'];
	}	

	/**
	 *	Loads Configuration from WDDX File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadWddxFile( $fileName )
	{
		$array	= XML_WDDX_FileReader::load( $fileName );
		foreach( $array as $sectionName => $sectionData )
			foreach( $sectionData as $key => $value )
				$this->pairs[$sectionName.".".$key]	= $value;
	}	
	
	/**
	 *	Loads Configuration from YAML File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadYamlFile( $fileName )
	{
		$array	= File_YAML_Reader::load( $fileName );
		foreach( $array as $sectionName => $sectionData )
			foreach( $sectionData as $key => $value )
				$this->pairs[$sectionName.".".$key]	= $value;
	}	
	
	/**
	 *	Loads Configuration from XML File.
	 *	@access		protected
	 *	@param		string		$fileName		File Name of Configuration File
	 *	@return		void
	 */
	protected function loadXmlFile( $fileName )
	{
		$root	= XML_ElementReader::readFile( $fileName );											//  get root element of XML file
		$this->pairs	= array();
		foreach( $root as $sectionNode )															//  iterate sections
		{
			$sectionName	= $sectionNode->getAttribute( 'name' );									//  get section name
			$this->loadXmlSection( $sectionNode, $sectionName );									//  read section
		}
		ksort( $this->pairs );																		//  sort resulting pairs by key
	}

	/**
	 *	Reads sections and values of a XML file node, recursivly, and stores pairs in-situ.
	 *	@access		protected
	 *	@param		XML_Element	$node			Section XML node to read
	 *	@param		string		$path			Path to this section
	 *	@return		void
	 */
	protected function loadXmlSection( $node, $path = NULL )
	{
		$path	.= $path ? '.' : '';																//  extend path by delimiter
		foreach( $node as $child )																	//  iterate node children
		{
			$name	= $child->getAttribute( 'name' );												//  get node name of child
			switch( $child->getName() ){															//  dispatch on node name
				case 'section':																		//  section node
					$this->loadXmlSection( $child, $path.$name );									//  load child section
					break;
				case 'value':																		//  pair node
					$type	= 'string';																//  default type: string
					if( $child->hasAttribute( 'type' ) )											//  type attribute is set
						$type	= $child->getAttribute( 'type' );									//  realize type attribute
					$value	= (string) $child;														//  convert node content to value string
					settype( $value, $type );														//  apply type to value
					$this->pairs[$path.$name]	= $value;											//  register pair
					break;
			}
		}
	}

	/**
	 *	Gernates Cache File Name and tries to load Configuration from Cache File.
	 *	@access		protected
	 *	@param		string		$cacheFile		File Name of Cache File
	 *	@param		int			$lastChange		Last Change of Configuration File
	 *	@return		bool
	 */
	protected function tryToLoadFromCache( $cacheFile, $lastChange )
	{
		if( !file_exists( $cacheFile ) )
			return FALSE;

		$lastCache	= @filemtime( $cacheFile );
		if( $lastCache && $lastChange <= $lastCache )
		{
			$content	= file_get_contents( $cacheFile );
			$array		= @unserialize( $content );
			if( is_array( $array ) )
			{
				$this->pairs	= $array;
				return TRUE;
			}
		}
		return FALSE;
	}
}
?>
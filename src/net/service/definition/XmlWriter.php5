<?php
/**
 *	Builder and Writer for XML Service Definitions.
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
 *	@package		net.service.definition
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.xml.dom.Builder' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Parser' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Builder and Writer for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@uses			File_Writer
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Net_Service_Definition_XmlWriter
{
	/**
	 *	Builds Service Definition Array statically and returns XML Service Definition String.
	 *	@access		public
	 *	@static
	 *	@param		array			$data				Service Point Definition Array
	 *	@return		string
	 */
	public static function build( $data )
	{
		$builder	= new XML_DOM_Builder();													//  new XML Builder Instance
		$root		= self::buildServicePoint( $data );											//  build Service Point Structure
		return $builder->build( $root );														//  build and return XML String
	}		
		
	/**
	 *	Builds and returns a single Service Structure, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		string			$serviceName		Name of Service
	 *	@param		array			$serviceData		Service Definition Array
	 *	@return		XML_DOM_Node
	 */
	protected static function buildService( $serviceName, $serviceData )
	{
		$node	=& new XML_DOM_Node( 'service' );												//  new Service Node
		$node->setAttribute( 'name', $serviceName );											//  set Service Name Attribute
		$node->setAttribute( 'class', $serviceData['class'] );									//  set Service Class Attribute
		$node->setAttribute( 'format', $serviceData['preferred'] );								//  set preferred Service Format Attribute
		$node->addChild( new XML_DOM_Node( 'description', $serviceData['description'] ) );		//  set Service Description
		self::buildServiceFormats( $serviceData, $node );										//  set Service Formats
		self::buildServiceParameters( $serviceData, $node );									//  set Service Parameters
		self::buildServiceRoles( $serviceData, $node );											//  set Service Roles
		if( isset( $serviceData['status'] ) && $serviceData['status'] )							//  Service Status is available
			$node->setAttribute( 'status', $serviceData['status']  );							//  set Service Status Attribute
		return $node;																			//  return Service Structure
	}

	/**
	 *	Builds a Service Formats Structure in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		array			$serviceData		Service Definition Array
	 *	@param		XML_DOM_Node	$serviceNode		Service Node to add Formats Structure to
	 *	@return		void
	 */
	protected static function buildServiceFormats( $serviceData, $serviceNode )
	{
		foreach( $serviceData['formats'] as $format )											//  iterate Service Formates
		{
			$node	= new XML_DOM_Node( 'format', $format );									//  build new Format Node
			$serviceNode->addChild( $node );													//  append Format Node to Service Node
		}
	}

	/**
	 *	Builds a Service Parameters Structure in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		array			$serviceData		Service Definition Array
	 *	@param		XML_DOM_Node	$serviceNode		Service Node to add Parameters Structure to
	 *	@return		void
	 */
	protected static function buildServiceParameters( $serviceData, $serviceNode )
	{
		if( !isset( $serviceData['parameters'] ) || !is_array( $serviceData['parameters'] ) )	//  no valid Parameters Definition
			return;
		if( !$serviceData['parameters'] )														//  no Parameters defined
			return;
		foreach( $serviceData['parameters'] as $parameterName => $parameterProperties )			//  iterate Parameters
		{
			$node	=& new XML_DOM_Node( 'parameter', $parameterName );							//  builds new Parameter Node
			foreach( $parameterProperties as $propertyName => $propertyValue )					//  iterate Parameter Attributes
			{
				if( is_bool( $propertyValue ) )													//  boolean Value
					$propertyValue	= $propertyValue ? "yes" : "no";							//  convert to String
				$node->setAttribute( $propertyName, $propertyValue );							//  set Parameter Attribute
			}
			$serviceNode->addChild( $node );													//  append Parameter Node to Service Node
		}
	}

	/**
	 *	Builds and returns a Service Point Structure, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		array			$data				Service Point Definition Array
	 *	@return		XML_DOM_Node
	 */
	protected static function buildServicePoint( $data )
	{
		$root	= new XML_DOM_Node( 'servicePoint' );											//  build Service Point Node
		$root->addChild( new XML_DOM_Node( 'title', $data['title'] ) );							//  set Service Point Title
		$root->addChild( new XML_DOM_Node( 'description', $data['description'] ) );				//  set Service Point Description
		$root->addChild( new XML_DOM_Node( 'url', $data['url'] ) );								//  set Service Point Example URL
		$root->addChild( new XML_DOM_Node( 'syntax', $data['syntax'] ) );						//  set service Point Example Syntax
		self::buildServices( $data['services'], $root );										//  build Services Structure
		return $root;																			//  return final Service Point Node
	}

	/**
	 *	Builds a Services Structure in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		array			$services			Services Definition Array
	 *	@param		XML_DOM_Node	$root				Service Point Node to add Services Structure to
	 *	@return		void
	 */
	protected static function buildServices( $services, $root )
	{
		$nodeServices	= new XML_DOM_Node( 'services' );										//  build Services Node
		foreach( $services as $serviceName => $serviceData )									//  iterate Services
		{
			$nodeService	= self::buildService( $serviceName, $serviceData );					//  build Service Structure
			$nodeServices->addChild( $nodeService );											//  append Service Structure to Services Node
		}
		$root->addChild( $nodeServices );														//  append Services Node to Service Point Node
	}

	/**
	 *	Builds a Service Roles Structure in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		array			$serviceData		Service Definition Array
	 *	@param		XML_DOM_Node	$serviceNode		Service Node to add Roles Structure to
	 *	@return		void
	 */
	protected static function buildServiceRoles( $serviceData, $serviceNode )
	{
		if( !( isset( $serviceData['roles'] ) && is_array( $serviceData['roles'] ) ) )			//  no valid Role Definition
			return;
		if( !$serviceData['roles'] )															//  no Roles defined
			return;
		foreach( $serviceData['roles'] as $role )												//  iterate Roles
		{
			$node	=& new XML_DOM_Node( 'role', $role ); 										//  new Role Node
			$serviceNode->addChild( $node );													//  append Role Node to Service Node
		}
	}

	/**
	 *	Writes XML Service Definition String from Service Definition Array to XML File and returns number of written Bytes.
	 *	@access		public
	 *	@static
	 *	@param		string			$fileName			File Name of XML File
	 *	@param		array			$data				Service Point Definition Array
	 *	@return		int				Number of written Bytes
	 */
	public static function save( $fileName, $data )
	{
		$xml	= self::build( $data );															//  build XML String
		$bytes	= File_Writer::save( $fileName, $xml );											//  write XML String to File
		return $bytes;																			//  return Number of written Bytes
	}
}
?>
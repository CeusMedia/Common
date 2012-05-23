<?php
/**
 *	Parser and Reader for XML Service Definitions.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceusmedia.com)
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
 *	@package		Net.Service.Definition
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
/**
 *	Parser and Reader for XML Service Definitions.
 *	@category		cmClasses
 *	@package		Net.Service.Definition
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 *	@deprecated		moved to cmModules::ENS
 *	@todo			to be removed in 0.7.3
 */
class Net_Service_Definition_XmlReader
{
	/**
	 *	Parses XML Service Definition statically and returns Service Data Array.
	 *	@access		public
	 *	@static
	 *	@param		string		$fileName		File Name of XML Service Definition
	 *	@param		bool		$validate		Flag: validate against DTD
	 *	@return		array
	 */
	public static function load( $fileName, $validate = FALSE )
	{
		if( $validate )
			self::validateFile( $fileName );

		$element	= XML_ElementReader::readFile( $fileName );
		$data		= self::readServicePoint( $element );
		return $data;
	}

	/**
	 *	Reads Service Information in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service Point XML Element
	 *	@param		array		$services	Reference to Service Point Service List
	 *	@return		void
	 */
	protected static function readService( $element, &$services )
	{
		$serviceName	= $element->getAttribute( 'name' );
		$service		= array(
			'class'			=> (string) $element->getAttribute( 'class' ),
			'description'	=> (string) $element->description,
			'formats'		=> array(),
			'preferred'		=> (string) $element->getAttribute( 'format' ),
			'filters'		=> array(),
			'parameters'	=> array(),
			'roles'			=> array(),
			'status'		=> NULL,
		);
		if( $element->hasAttribute( "status" ) )
			$service['status']	= $element->getAttribute( "status" );
		self::readServiceFilters( $element, $service );
		self::readServiceFormats( $element, $service );
		self::readServiceParameters( $element, $service );
		self::readServiceRoles( $element, $service );
		$services[$serviceName]	= $service;
	}

	/**
	 *	Reads Service Filters in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service Point XML Element
	 *	@param		array		$service	Reference to Service Definition Array
	 *	@return		void
	 */
	protected static function readServiceFilters( $element, &$service )
	{
		foreach( $element->filter as $filterElement )
		{
			$key	= trim( (string) $filterElement );
			if( !trim( $key ) )
				continue;
			$title	= NULL;
			if( $filterElement->hasAttribute( 'title' ) )
				$title	= $filterElement->getAttribute( 'title' );
			$service['filters'][$key]	= $title;
		}
	}

	/**
	 *	Reads Service Formats in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service Point XML Element
	 *	@param		array		$service	Reference to Service Definition Array
	 *	@return		void
	 */
	protected static function readServiceFormats( $element, &$service )
	{
		foreach( $element->format as $formatElement )
			$service['formats'][]	= strtolower( (string) $formatElement );
	}

	/**
	 *	Reads Service Parameters in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service XML Element
	 *	@param		array		$service	Reference to Service Definition Array
	 *	@return		void
	 */
	protected static function readServiceParameters( $element, &$service )
	{
		foreach( $element->parameter as $parameterElement )
		{
			$parameterName	= (string) $parameterElement;
			$attributes		= array(
				'mandatory'	=> NULL,
				'type'		=> NULL,
				'preg'		=> NULL,
				'filters'	=> array(),
				'title'		=> NULL,
			);
			foreach( $parameterElement->getAttributes() as $key => $value )
			{
				if( strtolower( $key ) == "mandatory" )
					$attributes['mandatory']	= strtolower( $value ) == "yes" ? TRUE : FALSE;
				else if( strtolower( $key ) == "filters" )
				{
					foreach( explode( ",", $value ) as $filter )
						if( trim( $filter ) )
							$attributes['filters'][]	= trim( $filter );
				}
				else
					$attributes[strtolower( $key)]	= $value;
			}
			$service['parameters'][$parameterName]	= $attributes;
		}
	}

	/**
	 *	Reads all Service Point Information and returns Definition Array, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service Point XML Element
	 *	@return		array
	 */
	protected static function readServicePoint( $element )
	{
		$data	= array(
			'title'			=> (string) $element->title,
			'description'	=> (string) $element->description,
			'url'			=> (string) $element->url,
			'syntax'		=> (string) $element->syntax,
			'filters'		=> array(),
			'services'		=> array(),
		);
		self::readServiceFilters( $element, $data );
		foreach( $element->services->service as $serviceElement )
			self::readService( $serviceElement, $data['services'] );
		return $data;
	}
	
	/**
	 *	Reads Service Roles in situ, can be overwritten.
	 *	@access		protected
	 *	@static
	 *	@param		XML_Element	$element	Service Point XML Element
	 *	@param		array		$service	Reference to Service Definition Array
	 *	@return		void
	 */
	protected static function readServiceRoles( $element, &$service )
	{
		foreach( $element->role as $role )												//  iterate Roles
			$service['roles'][] = (string) $role;										//  note Role
	}

	/**
	 *	Validates Service XML File againt linked DTD and throws Exception if invalid.
	 *	@access		protected
	 *	@static
	 *	@param		string		$fileName	File Name of Service XML File.
	 *	@return		void
	 */
	protected static function validateFile( $fileName )
	{
		$dom = new DOMDocument;
		$dom->load( $fileName );
		if( !@$dom->validate() )
			throw new RuntimeException( 'Service XML File is not valid or no DTD given.' );
	}
}
?>
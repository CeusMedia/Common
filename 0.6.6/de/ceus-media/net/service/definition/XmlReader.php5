<?php
/**
 *	Parser and Reader for XML Service Definitions.
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
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.xml.ElementReader' );
/**
 *	Parser and Reader for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6.5
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
			'parameters'	=> array(),
			'roles'			=> array(),
			'status'		=> NULL,
		);
		if( $element->hasAttribute( "status" ) )
			$service['status']	= $element->getAttribute( "status" );
		self::readServiceFormats( $element, $service );
		self::readServiceParameters( $element, $service );
		self::readServiceRoles( $element, $service );
		$services[$serviceName]	= $service;
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
			$validators		= array();
			foreach( $parameterElement->getAttributes() as $key => $value )
			{
				if( strtolower( $key ) == "mandatory" )
					$value	= strtolower( $value ) == "yes" ? TRUE : FALSE;
				$validators[strtolower( $key)]	= $value;
			}
			$service['parameters'][$parameterName]	= $validators;
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
			'services'		=> array(),
		);
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
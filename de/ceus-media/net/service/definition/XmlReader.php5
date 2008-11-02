<?php
import( 'de.ceus-media.xml.ElementReader' );
/**
 *	Parser and Reader for XML Service Definitions.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Parser and Reader for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Net_Service_Definition_XmlReader
{
	/**
	 *	Parses XML Service Definition statically and returns Service Data Array.
	 *	@access		public
	 *	@param		string		$fileName		File Name of XML Service Definition
	 *	@return		array
	 */
	public static function load( $fileName )
	{
		$element				= XML_ElementReader::readFile( $fileName );
		$data['title']			= (string) $element->title;
		$data['description']	= (string) $element->description;
		$data['url']			= (string) $element->url;
		$data['syntax']			= (string) $element->syntax;
		$data['services']		= array();
		foreach( $element->services->service as $serviceElement )
		{
			$serviceName	= $serviceElement->getAttribute( 'name' );
			$service	= array(
				'class'			=> (string) $serviceElement->getAttribute( 'class' ),
				'description'	=> (string) $serviceElement->description,
				'formats'		=> array(),
				'preferred'		=> (string) $serviceElement->getAttribute( 'format' ),
			);
			foreach( $serviceElement->format as $formatElement )
				$service['formats'][]	= strtolower( (string) $formatElement );
			$parameters	= array();
			foreach( $serviceElement->parameter as $parameterElement )
			{
				$parameterName	= (string) $parameterElement;
				$validators		= array();
				foreach( $parameterElement->getAttributes() as $key => $value )
				{
					if( strtolower( $key ) == "mandatory" )
						$value	= strtolower( strtolower( $value ) ) == "yes" ? TRUE : FALSE;
					$validators[strtolower( $key)]	= $value;
				}
				$parameters[$parameterName]	= $validators;
			}
			if( $parameters )
				$service['parameters']	= $parameters;
			if( $serviceElement->hasAttribute( "status" ) )
				$service['status']	= strtolower( $serviceElement->getAttribute( "status" ) );
			$data['services'][$serviceName]	= $service;
		}
		return $data;
	}
}
?>
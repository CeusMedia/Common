<?php
import( 'de.ceus-media.xml.dom.Builder' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Parser' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Builder and Writer for XML Service Definitions.
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
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
/**
 *	Builder and Writer for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Net_Service_Definition_XmlWriter
{
	/**
	 *	Builds Service Definition Array statically and returns XML Service Definition String.
	 *	@access		public
	 *	@param		array		$data		Service Definition Array
	 *	@return		string
	 */
	public static function build( $data )
	{
		$root	= new XML_DOM_Node( 'servicePoint' );
		$root->addChild( new XML_DOM_Node( 'title', $data['title'] ) );
		$root->addChild( new XML_DOM_Node( 'description', $data['description'] ) );
		$root->addChild( new XML_DOM_Node( 'url', $data['url'] ) );
		$root->addChild( new XML_DOM_Node( 'syntax', $data['syntax'] ) );
		$nodeServices	= new XML_DOM_Node( 'services' );

		foreach( $data['services'] as $serviceName => $serviceData )
		{
			$nodeService	=& new XML_DOM_Node( 'service' );
			$nodeService->setAttribute( 'name', $serviceName );
			$nodeService->setAttribute( 'class', $serviceData['class'] );
			$nodeService->setAttribute( 'format', $serviceData['preferred'] );
			$nodeService->addChild( new XML_DOM_Node( 'description', $serviceData['description'] ) );	

			foreach( $serviceData['formats'] as $format )
			{
				$nodeService->addChild( new XML_DOM_Node( 'format', $format ) );	
			}
			if( isset( $serviceData['parameters'] ) && is_array( $serviceData['parameters'] ) )
			{
				foreach( $serviceData['parameters'] as $parameterName => $parameterProperties )
				{
					$nodeParameter	=& new XML_DOM_Node( 'parameter', $parameterName );
					if( !is_array( $parameterProperties ) )
						continue;
					foreach( $parameterProperties as $propertyName => $propertyValue )
					{
						if( is_bool( $propertyValue ) )
							$propertyValue	= $propertyValue ? "yes" : "no";
					
						$nodeParameter->setAttribute( $propertyName, $propertyValue );
					}
					$nodeService->addChild( $nodeParameter );
				}
			}
			if( isset( $serviceData['status'] ) )
				$nodeService->setAttribute( 'status', $serviceData['status'] );	
			$nodeServices->addChild( $nodeService );
		}
		$root->addChild( $nodeServices );
		$builder	= new XML_DOM_Builder();
		$xml		= $builder->build( $root );
		return $xml;
	}

	/**
	 *	Builds and writes XML Service Definition String from Service Definition Array to XML File.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML File
	 *	@param		array		$data		Service Definition Array
	 *	@return		int
	 */
	public static function save( $fileName, $data )
	{
		$xml	= self::build( $data );
		return File_Writer::save( $fileName, $xml );
	}
}
?>
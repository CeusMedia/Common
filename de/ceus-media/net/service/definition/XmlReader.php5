<?php
import( 'de.ceus-media.xml.ElementReader' );
/**
 *	Parser and Reader for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Parser and Reader for XML Service Definitions.
 *	@package		net.service.definition
 *	@uses			XML_ElementReader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
		$element			= XML_ElementReader::readFile( $fileName );
		$data['title']		= (string) $element->title;
		$data['url']		= (string) $element->url;
		$data['syntax']		= (string) $element->syntax;
		$data['services']	= array();
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
<?php
import( 'de.ceus-media.xml.dom.Builder' );
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Parser' );
/**
 *	Builder and Parser for XML Service Definitions.
 *	@package		service
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Builder and Parser for XML Service Definitions.
 *	@package		service
 *	@uses			XML_DOM_Builder
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Parser
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class Service_XmlDefinition
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
	 *	Parses XML Service Definition statically and returns Service Data Array.
	 *	@access		public
	 *	@param		string		$xml		XML Service Definition String
	 *	@return		array
	 */
	public static function parse( $xml )
	{
		$parser	= new XML_DOM_Parser;
		$tree	= $parser->parse( $xml );
		$data['title']		= $tree->getChild( "title" )->getContent();
		$data['url']		= $tree->getChild( "url" )->getContent();
		$data['syntax']		= $tree->getChild( "syntax" )->getContent();
		$data['services']	= array();
		
		foreach( $tree->getChild( "services" )->getChildren( "service" ) as $serviceNode )
		{
			$serviceName	= $serviceNode->getAttribute( 'name' );
			$service	= array(
				'class'			=> $serviceNode->getAttribute( 'class' ),
				'description'	=> $serviceNode->getChild( "description" )->getContent(),
				'formats'		=> array(),
				'preferred'		=> $serviceNode->getAttribute( 'format' ),
			);
			foreach( $serviceNode->getChildren( "format" ) as $format )
				$service['formats'][]	= $format->getContent();
			$parameters	= array();
			foreach( $serviceNode->getChildren( "parameter" ) as $parameter )
			{
				$parameterName	= $parameter->getContent();
				$validators		= array();
				foreach( $parameter->getAttributes() as $key => $value )
					$validators[$key]	= $value;
				$parameters[$parameterName]	= $validators;
			}
			if( $parameters )
				$service['parameters']	= $parameters;
			if( $serviceNode->hasAttribute( "status" ) )
				$service['status']	= $serviceNode->getAttribute( "status" );
			$data['services'][$serviceName]	= $service;
		}
		return $data;
	}
}
?>
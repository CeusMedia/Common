<?php
import( 'de.ceus-media.service.ServicePoint' );
import( 'de.ceus-media.file.YamlReader' );
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		ServicePoint
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Point with YAML Definition File.
 *	@package		service
 *	@implements		ServicePoint
 *	@uses			File_Yaml_Reader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
class YamlServicePoint implements ServicePoint
{
	/**	@protected					Array of Services */	
	protected $services	= array();
	
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			Service Definition File Name
	 *	@param		string			Service Definition Cache File Name
	 *	@return		void
	 */
	public function __construct( $fileName, $cacheFile = false )
	{
		if( !file_exists( $fileName ) )
			throw new Exception( "Definition File '".$fileName."' is not existing." );
		if( $cacheFile && file_exists( $cacheFile ) && filemtime( $fileName ) <= filemtime( $cacheFile ) )
			return $this->services	= unserialize( file_get_contents( $cacheFile ) );
		$this->services	= File_Yaml_Reader::loadYAML( $fileName );
		if( $cacheFile )
			file_put_contents( $cacheFile, serialize( $this->services ) );
	}

	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@param		array|Object	Array or Object of Request Data
	 *	@return		string			Response String of Service	
	 */
	public function callService( $serviceName, $responseFormat = NULL, $requestData = NULL )
	{
		$this->checkServiceCall( $serviceName, $responseFormat, $requestData );
		if( !$responseFormat )
			$responseFormat	= $this->getDefaultServiceFormat( $serviceName );
		
		$class		= $this->services['services'][$serviceName]['class'];
		$object		= new $class;
		$response	= $object->$serviceName( $responseFormat, $requestData );
		return $response;
	}

	/**
	 *	Checks Service, Service Method and Response Format and throws Exception is something is wrong.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@return		bool	
	 */
	protected function checkServiceCall( $serviceName, $responseFormat = false )
	{
		$this->checkServiceDefinition( $serviceName );
		$this->checkServiceMethod( $serviceName );
		$this->checkServiceFormat( $serviceName, $responseFormat );
	}
	
	/**
	 *	Checks Service and throws Exception if Service is not existing.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	protected function checkServiceDefinition( $serviceName )
	{
		if( !isset( $this->services['services'][$serviceName] ) )
			throw new Exception( "Service '".$serviceName."' is not existing." );
		if( !isset( $this->services['services'][$serviceName]['class'] ) )
			throw new Exception( "No Class definied for Service '".$serviceName."' is not defined." );
	}

	/**
	 *	Checks Service Method and throws Exception if Service Method is not existing.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	protected function checkServiceMethod( $serviceName )
	{
		$className	= $this->services['services'][$serviceName]['class'];
		if( !in_array( $serviceName, get_class_methods( $className ) ) )
			throw new Exception( "Method '".$serviceName."' does not exist in Class '".$className."'" );
	}

	/**
	 *	Checks Service Response Format and throws Exception if Format is invalid or no Format and no default Format is set.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@return		void	
	 */
	protected function checkServiceFormat( $serviceName, $responseFormat )
	{
		if( $responseFormat )
		{
			if( !in_array( $responseFormat, $this->services['services'][$serviceName]['formats'] ) )
				throw new Exception( "Response Format '".$responseFormat."' for Service '".$serviceName."' is not available." );
			return true;
		}
		if( !$this->getDefaultServiceFormat( $serviceName ) )
			throw new Exception( "No Format given and no default Format set for Service '".$serviceName."'." );
	}

	/**
	 *	Returns preferred Output Formats if defined.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Default Service Response Format, if defined
	 */
	public function getDefaultServiceFormat( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		$responseFormats	= $this->services['services'][$serviceName]['formats'];
		if( !isset( $this->services['services'][$serviceName]['preferred'] ) )
			return "";
		$default	=  $this->services['services'][$serviceName]['preferred'];
		if( !in_array( $default, $responseFormats ) )
			return "";
		return $default;
	}

	/**
	 *	Returns Class of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Class of Service
	 */
	public function getServiceClass( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['class'];
	}
	
	/**
	 *	Returns Description of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Description of Service
	 */
	public function getServiceDescription( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		if( isset( $this->services['services'][$serviceName]['description'] ) )
			return $this->services['services'][$serviceName]['description'];
		return "";
	}

	/**
	 *	Returns available Response Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array			Response Formats of this Service
	 */
	public function getServiceFormats( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['formats'];
	}
	
	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array			Services in Service Point
	 */
	public function getServices()
	{
		return array_keys( $this->services['services'] );
	}
	
	/**
	 *	Returns Array for preferred Service Examples.
	 *	@access		public
	 *	@return		array			Array for preferred Service Examples
	 */
	public function getServiceExamples()
	{
		$list	= array();
		foreach( $this->services['services'] as $serviceName => $serviceData )
		{
			if( isset( $serviceData['preferred'] ) )
			{
				$list[]	= array(
					'service'		=> $serviceName,
					'format'		=> $serviceData['preferred'],
					'description'	=> $serviceData['description']
				);
			}
		}
		return $list;
	}

	/**
	 *	Returns Syntax of Service Point.
	 *	@access		public
	 *	@return		string			Syntax of Service Point
	 */
	public function getServicesSyntax()
	{
		return $this->services['syntax'];
	}

	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string			Title of Service Point
	 */
	public function getServicesTitle()
	{
		return $this->services['title'];
	}
}
?>
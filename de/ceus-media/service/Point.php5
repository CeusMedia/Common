<?php
import( 'de.ceus-media.service.interface.Point' );
/**
 *	Access Point for Service Calls.
 *	A different Service Parameter Validator Class can be used by setting static Member "validatorClass".
 *	If a different Validator Class should be used, it needs to be imported before.
 *	A different Service Definition Loader Class can be used by setting static Member "loaderClass".
 *	If a different Loader Class should be used, it needs to be imported before.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@uses			Service_ParameterValidator
 *	@uses			Service_Definition_Loader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.3
 */
/**
 *	Access Point for Service Calls.
 *	A different Service Parameter Validator Class can be used by setting static Member "validatorClass".
 *	If a different Validator Class should be used, it needs to be imported before.
 *	A different Service Definition Loader Class can be used by setting static Member "loaderClass".
 *	If a different Loader Class should be used, it needs to be imported before.
 *	@package		service
 *	@implements		Service_Interface_Point
 *	@uses			Service_ParameterValidator
 *	@uses			Service_Definition_Loader
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.3
 */
class Service_Point implements Service_Interface_Point
{
	/**	@var		string				$defaultLoader		Default Definition Loader Class */
	protected $defaultLoader			= "Service_Definition_Loader";
	/**	@var		string				$defaultValidator	Default Validator Class */
	protected $defaultValidator			= "Service_ParameterValidator";
	/**	@var		string				$validatorClass		Definition Loader Class to use */
	public static $loaderClass			= "Service_Definition_Loader";
	/**	@var		string				$validatorClass		Validator Class to use */
	public static $validatorClass		= "Service_ParameterValidator";
	/**	@protected		array			$services			Array of Services */	
	protected $services	= array();
	
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	public function __construct( $fileName, $cacheFile = NULL )
	{
		$this->loadServices( $fileName, $cacheFile );
		if( self::$validatorClass == $this->defaultValidator )
			import( 'de.ceus-media.service.ParameterValidator' );
		$this->validator	= new self::$validatorClass;
	}

	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@param		string				$responseFormat		Format of Service Response
	 *	@param		ADT_List_Dictionary	$requestData		Array or Object of Request Data
	 *	@return		string									Response String of Service	
	 */
	public function callService( $serviceName, $responseFormat = NULL, $requestData = NULL )
	{
		$this->checkServiceDefinition( $serviceName );
		$this->checkServiceMethod( $serviceName );
		$this->checkServiceFormat( $serviceName, $responseFormat );
		$this->checkServiceParameters( $serviceName, $requestData );
		if( !$responseFormat )
			$responseFormat	= $this->getDefaultServiceFormat( $serviceName );
		
		$class		= $this->services['services'][$serviceName]['class'];
		$object		= new $class;
		$response	= $object->$serviceName( $responseFormat, $requestData );
		return $response;
	}

	/**
	 *	Checks Service and throws Exception if Service is not existing.
	 *	@access		protected
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	protected function checkServiceDefinition( $serviceName )
	{
		if( !isset( $this->services['services'][$serviceName] ) )
			throw new InvalidArgumentException( 'Service "'.$serviceName.'" is not existing.' );
		if( !isset( $this->services['services'][$serviceName]['class'] ) )
			throw new Exception( 'No Service Class definied for Service "'.$serviceName.'".' );
	}

	/**
	 *	Checks Service Method and throws Exception if Service Method is not existing.
	 *	@access		protected
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	protected function checkServiceMethod( $serviceName )
	{
		if( !isset( $this->services['services'][$serviceName] ) )
			throw new Exception( "Service '".$serviceName."' is not existing." );
		$className	= $this->services['services'][$serviceName]['class'];
		if( !class_exists( $className ) && !$this->loadServiceClass( $className ) )
			throw new RuntimeException( 'Service Class "'.$className.'" is not existing.' );
		$methods	= get_class_methods( $className );
		if( !in_array( $serviceName, $methods ) )
			throw new BadMethodCallException( 'Method "'.$serviceName.'" does not exist in Service Class "'.$className.'".' );
	}

	/**
	 *	Checks Service Response Format and throws Exception if Format is invalid or no Format and no default Format is set.
	 *	@access		protected
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@param		string				$responseFormat		Format of Service Response
	 *	@return		void	
	 */
	protected function checkServiceFormat( $serviceName, $responseFormat )
	{
		if( $responseFormat )
		{
			if( !in_array( $responseFormat, $this->services['services'][$serviceName]['formats'] ) )
				throw new Exception( 'Response Format "'.$responseFormat.'" for Service "'.$serviceName.'" is not available.' );
			return true;
		}
		if( !$this->getDefaultServiceFormat( $serviceName ) )
			throw new Exception( 'No Response Format given and no default Response Format set for Service "'.$serviceName.'".' );
	}

	/**
	 *	Checks Service Parameters and throws Exception is something is wrong.
	 *	@access		protected
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@param		arrray				$parameters			Array of requested Parameters
	 *	@return		void	
	 */
	protected function checkServiceParameters( $serviceName, $parameters )
	{
		if( !isset( $this->services['services'][$serviceName]['parameters'] ) )
			return;
		foreach( $this->services['services'][$serviceName]['parameters'] as $field => $rules )
		{
			if( !$rules )
				continue;
			$parameter	= isset( $parameters[$field] ) ? $parameters[$field] : NULL;
			try
			{
				$this->validator->validateParameterValue( $rules, $parameter );
			}
			catch( InvalidArgumentException $e )
			{
				throw new InvalidArgumentException( 'Parameter "'.$field.'" for Service "'.$serviceName.'" is invalid ( '.$e->getMessage().' ).' );			
			}
		}
	}

	/**
	 *	Returns preferred Output Formats if defined.
	 *	@access		public
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		string									Default Service Response Format, if defined
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
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		string									Class of Service
	 */
	public function getServiceClass( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['class'];
	}
	
	/**
	 *	Returns Description of Service.
	 *	@access		public
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		string									Description of Service
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
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		array									Response Formats of this Service
	 */
	public function getServiceFormats( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['formats'];
	}
	
	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array									Services in Service Point
	 */
	public function getServices()
	{
		return array_keys( $this->services['services'] );
	}
	
	/**
	 *	Returns Array for preferred Service Examples.
	 *	@access		public
	 *	@return		array									Array for preferred Service Examples
	 *	@deprecated	should not be used
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
	 *	Returns available Formats of Service.
	 *	@access		public
	 *	@param		string				$serviceName		Name of Service to call 
	 *	@return		array									Parameters of Service
	 */
	public function getServiceParameters( $serviceName )
	{
		if( isset( $this->services['services'][$serviceName]['parameters'] ) )
			return $this->services['services'][$serviceName]['parameters'];
		return array();
	}

	/**
	 *	Returns Syntax of Service Point.
	 *	@access		public
	 *	@return		string									Syntax of Service Point
	 */
	public function getSyntax()
	{
		return $this->services['syntax'];
	}

	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string									Title of Service Point
	 */
	public function getTitle()
	{
		return $this->services['title'];
	}
	
	/**
	 *	Loads Service Class, to be overwritten.
	 *	@access		protected
	 *	@param		string				$className			Class Name of Class to load
	 *	@return		bool
	 */
	protected function loadServiceClass( $className )
	{
		return false;
	}
	
	/**
	 *	Loads Service Definitions from XML or YAML File.
	 *	@access		protected
	 *	@param		string				$fileName			Service Definition File Name
	 *	@param		string				$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServices( $fileName, $cacheFile = NULL )
	{
		if( self::$loaderClass == $this->defaultLoader )
			import( 'de.ceus-media.service.definition.Loader' );
		$this->loader	= new self::$loaderClass;
		$this->services	= $this->loader->loadServices( $fileName, $cacheFile );
	}
}
?>
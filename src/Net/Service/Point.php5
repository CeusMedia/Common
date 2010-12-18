<?php
/**
 *	Access Point for Service Calls.
 *	A different Service Parameter Validator Class can be used by setting static Member "validatorClass".
 *	If a different Validator Class should be used, it needs to be imported before.
 *	A different Service Definition Loader Class can be used by setting static Member "loaderClass".
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
 *	If a different Loader Class should be used, it needs to be imported before.
 *	@category		cmClasses
 *	@package		Net.Service
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
/**
 *	Access Point for Service Calls.
 *	A different Service Parameter Validator Class can be used by setting static Member "validatorClass".
 *	If a different Validator Class should be used, it needs to be imported before.
 *	A different Service Definition Loader Class can be used by setting static Member "loaderClass".
 *	If a different Loader Class should be used, it needs to be imported before.
 *	@category		cmClasses
 *	@package		Net.Service
 *	@implements		Net_Service_Interface_Point
 *	@uses			Net_Service_Parameter_Validator
 *	@uses			Net_Service_Parameter_Filter
 *	@uses			Net_Service_Definition_Loader
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.6.3
 *	@version		$Id$
 */
class Net_Service_Point implements Net_Service_Interface_Point
{
	/**	@var		string			$defaultLoader		Default Definition Loader Class */
	protected $defaultLoader		= "Net_Service_Definition_Loader";
	/**	@var		string			$defaultValidator	Default Validator Class */
	protected $defaultFilter		= "Net_Service_Parameter_Filter";
	/**	@var		string			$defaultValidator	Default Validator Class */
	protected $defaultValidator		= "Net_Service_Parameter_Validator";
	/**	@var		string			$validatorClass		Definition Loader Class to use */
	public static $loaderClass		= "Net_Service_Definition_Loader";
	/**	@var		string			$filterClass		Filter Class to use */
	public static $filterClass		= "Net_Service_Parameter_Filter";
	/**	@var		string			$validatorClass		Validator Class to use */
	public static $validatorClass	= "Net_Service_Parameter_Validator";
	/**	@var		array			$services			Array of Services */	
	protected $services				= array();
	/**	@var		mixed			$validator			Validator Class */	
	protected $validator			= null;
	
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$fileName			Service Definition File Name
	 *	@param		string			$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	public function __construct( $fileName, $cacheFile = NULL )
	{
		$this->loadServices( $fileName, $cacheFile );												//  load Service Definition from File
		$this->filter	= new self::$filterClass;													//  create Filter Object
		$this->validator	= new self::$validatorClass;											//  create Validator Object
	}

	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@param		array			$requestData		Array (or Object with ArrayAccess Interface) of Request Data
	 *	@return		string								Response String of Service	
	 */
	public function callService( $serviceName, $responseFormat = NULL, $requestData = NULL )
	{
		$this->checkServiceDefinition( $serviceName );
		$this->checkServiceMethod( $serviceName );
		$this->checkServiceFormat( $serviceName, $responseFormat );
		$this->checkServiceParameters( $serviceName, $requestData );
		if( !$responseFormat )
			$responseFormat	= $this->getDefaultServiceFormat( $serviceName );

		$parameters	= array( 'format' => $responseFormat );
		
		if( isset( $this->services['services'][$serviceName]['parameters'] ) )
		{
			$names	= $this->services['services'][$serviceName]['parameters'];
			foreach( $names as $name => $rules )
			{
				if( empty( $requestData[$name] ) )													//  no Value given by Request
				{
					$default	= empty( $rules['default'] ) ? NULL : $rules['default'];			//  get Default Value
					$value		= $default;
				}
				else
				{
					$type		= empty( $rules['type'] ) ? "string" : $rules['type'];				//  get Type of Parameter
					$value		= $requestData[$name];
					if( $type == "array" && is_string( $value ) )
						$value	= parse_str( $value );												//  realise Request Value
				}
				$serviceFilters	= $this->services['services'][$serviceName]['filters'];				//  global Service Filters
				foreach( array_keys( $serviceFilters ) as $filterMethod )							//  iterate
				{
					$value	= $this->filter->applyFilter( $filterMethod, $value );					//  apply Filter to Paramater Value
				}
				if( !empty( $rules['filters'] ) )													//  local Parameter Filters
				{
					foreach( $rules['filters'] as $filter )											//  iterate
					{
						$value	= $this->filter->applyFilter( $filter, $value );					//  apply Filter to Paramater Value
					}
				}
				$parameters[$name]	= $value;
			}
		}
		
		$class		= $this->services['services'][$serviceName]['class'];
		$object		= new $class;
		$response	= call_user_func_array( array( $object, $serviceName ), $parameters );
		return $response;
	}

	/**
	 *	Checks Service and throws Exception if Service is not existing.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	public function checkServiceDefinition( $serviceName )
	{
		if( !isset( $this->services['services'][$serviceName] ) )
			throw new BadFunctionCallException( 'Service "'.$serviceName.'" is not existing' );
		if( !isset( $this->services['services'][$serviceName]['class'] ) )
			throw new RuntimeException( 'No Service Class definied for Service "'.$serviceName.'"' );
	}

	/**
	 *	Checks Service Method and throws Exception if Service Method is not existing.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		void	
	 */
	protected function checkServiceMethod( $serviceName )
	{
		if( !isset( $this->services['services'][$serviceName] ) )
			throw new BadFunctionCallException( 'Service "'.$serviceName.'" is not existing' );
		$className	= $this->services['services'][$serviceName]['class'];
		if( !class_exists( $className ) && !$this->loadServiceClass( $className ) )
			throw new RuntimeException( 'Service Class "'.$className.'" is not existing' );
		$methods	= get_class_methods( $className );
		if( !in_array( $serviceName, $methods ) )
			throw new BadMethodCallException( 'Method "'.$serviceName.'" does not exist in Service Class "'.$className.'"' );
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
				throw new InvalidArgumentException( 'Response Format "'.$responseFormat.'" for Service "'.$serviceName.'" is not available' );
			return true;
		}
		if( !$this->getDefaultServiceFormat( $serviceName ) )
			throw new RuntimeException( 'No Response Format given and no default Response Format set for Service "'.$serviceName.'"' );
	}

	/**
	 *	Checks Service Parameters and throws Exception is something is wrong.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		arrray			$parameters			Array of requested Parameters
	 *	@return		void	
	 */
	protected function checkServiceParameters( $serviceName, $parameters )
	{
		if( !isset( $this->services['services'][$serviceName]['parameters'] ) )
			return;
		foreach( $this->services['services'][$serviceName]['parameters'] as $name => $rules )
		{
			if( !$rules )
				continue;

			$type	= empty( $rules['type'] ) ? "string" : $rules['type'];							//  get Type of Parameter
			$value	= empty( $rules['default'] ) ? NULL : $rules['default'];						//  get Default Value
			if( isset( $parameters[$name] ) )
			{
				$value	= $parameters[$name];
				if( $type == "array" && is_string( $value ) )
					$value	= parse_str( $value );
			}

			try
			{
				$this->validator->validateParameterValue( $rules, $value );
			}
			catch( InvalidArgumentException $e )
			{
				throw new InvalidArgumentException( 'Parameter "'.$name.'" for Service "'.$serviceName.'" failed Rule "'.$e->getMessage().'"' );			
			}
		}
	}

	/**
	 *	Returns default Type of Service Parameter.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		arrray			$parameterName		Name oif Parameter to get default Type for
	 *	@return		string
	 */
	public function getServiceDefaultParameterType( $serviceName, $parameterName )
	{
		$type	= "unknown";
		$parameters	= $this->getServiceParameters( $serviceName );
		if( !$parameters )
			throw new InvalidArgumentException( 'Service "'.$serviceName.'" does not receive any Parameters' );
		if( !isset( $parameters[$parameterName] ) )
			throw new InvalidArgumentException( 'Parameter "'.$parameterName.'" for Service "'.$serviceName.'" is not defined' );
		$parameter	= $parameters[$parameterName];
		if( isset( $parameter['type'] ) )
			$type	= $parameter['type'];
		return $type;
	}

	protected function realizeParameterType( $value, $type )
	{
		switch( $type )
		{
			case 'array':
				$value	= parse_str( (string) $value );
				break;
			case 'string':
			case 'int':
			case 'integer':
			case 'float':
			case 'double':
			case 'real':
			case 'bool':
			case 'boolean':
#				settype( $value, $type );
				break;
			default:
				$value	= $default;
		}
		return $value;
	}

	/**
	 *	Returns preferred Output Formats if defined.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string								Default Service Response Format, if defined
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
	 *	Returns Description of Service Point.
	 *	@access		public
	 *	@return		string								Title of Service Point
	 */
	public function getDescription()
	{
		return $this->services['description'];	
	}

	/**
	 *	Returns Class of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string								Class of Service
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
	 *	@return		string								Description of Service
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
	 *	@return		array								Response Formats of this Service
	 */
	public function getServiceFilters( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['filters'];
	}

	/**
	 *	Returns available Response Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array								Response Formats of this Service
	 */
	public function getServiceFormats( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['formats'];
	}

	/**
	 *	Returns Roles having Access to Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array								List of allowed Roles
	 */
	public function getServiceRoles( $serviceName )
	{
		$this->checkServiceDefinition( $serviceName );
		return $this->services['services'][$serviceName]['roles'];
	}
	
	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array								Services in Service Point
	 */
	public function getServices()
	{
		return array_keys( $this->services['services'] );
	}
	
	/**
	 *	Returns Array for preferred Service Examples.
	 *	@access		public
	 *	@return		array								Array for preferred Service Examples
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
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array								Parameters of Service
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
	 *	@return		string								Syntax of Service Point
	 */
	public function getSyntax()
	{
		return $this->services['syntax'];
	}

	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string								Title of Service Point
	 */
	public function getTitle()
	{
		return $this->services['title'];
	}
	
	/**
	 *	Loads Service Class, to be overwritten.
	 *	@access		protected
	 *	@param		string			$className			Class Name of Class to load
	 *	@return		bool
	 */
	protected function loadServiceClass( $className )
	{
		throw new RuntimeException( 'No Service Class Loader implemented. Service Class "'.$className.'" has not been loaded' );
	}
	
	/**
	 *	Loads Service Definitions from XML or YAML File.
	 *	@access		protected
	 *	@param		string			$fileName			Service Definition File Name
	 *	@param		string			$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	protected function loadServices( $fileName, $cacheFile = NULL )
	{
		$this->loader	= new self::$loaderClass;
		$this->services	= $this->loader->loadServices( $fileName, $cacheFile );
	}
}
?>
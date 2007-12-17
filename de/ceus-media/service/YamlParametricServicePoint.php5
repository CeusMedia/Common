<?php
import( 'de.ceus-media.service.ServicePoint' );
import( 'de.ceus-media.service.YamlServicePoint' );
import( 'de.ceus-media.service.ParametricServicePoint' );
import( 'de.ceus-media.service.ParameterValidator' );
/**
 *	Service Point for parametric Services with YAML Definition File.
 *	@package		service
 *	@extends		YamlServicePoint
 *	@implements		ServicePoint
 *	@implements		ParametricServicePoint
 *	@uses			ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Point for parametric Services with YAML Definition File.
 *	@package		service
 *	@extends		YamlServicePoint
 *	@implements		ServicePoint
 *	@implements		ParametricServicePoint
 *	@uses			ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
class YamlParametricServicePoint extends YamlServicePoint implements ServicePoint, ParametricServicePoint
{
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$fileName			Service Definition File Name
	 *	@param		string			$cacheFile			Service Definition Cache File Name
	 *	@return		void
	 */
	public function __construct( $fileName, $cacheFile = false )
	{
		parent::__construct( $fileName, $cacheFile );
		$this->validator	= new ParameterValidator( $this->services );
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
		return parent::callService( $serviceName, $responseFormat, $requestData );
	}
	
	/**
	 *	Checks Service, Service Method, Response Format and Parameters and throws Exception is something is wrong.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format to output Service Results
	 *	@return		void	
	 */
	protected function checkServiceCall( $serviceName, $responseFormat = false, $parameters = array() )
	{
		parent::checkServiceCall( $serviceName, $responseFormat );
		$this->checkServiceParameters( $serviceName, $parameters );
	}
	
	/**
	 *	Checks Service Parameters and throws Exception is something is wrong.
	 *	@access		protected
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format to output Service Results
	 *	@return		void	
	 */
	protected function checkServiceParameters( $serviceName, $parameters )
	{
		if( !isset( $this->services['services'][$serviceName]['parameters'] ) )
			return;
		foreach( $this->services['services'][$serviceName]['parameters'] as $field => $rules )
		{
			try
			{
				if( $rules )
				{
					$parameter	= isset( $parameters[$field] ) ? $parameters[$field] : null;
					$this->validator->validateFieldValue( $rules, $parameter );
				}
			}
			catch( Validation_Exception $e )
			{
				throw new Exception( "Parameter '".$field."' is invalid ( ".$e->getMessage()." )." );			
			}
		}
	}
	
	/**
	 *	Returns Class of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Class of Service
	 */
	public function getServiceClass( $serviceName )
	{
		return parent::getServiceClass( $serviceName );
	}
	
	/**
	 *	Returns Description of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Description of Service
	 */
	public function getServiceDescription( $serviceName )
	{
		return parent::getServiceDescription( $serviceName );
	}


	/**
	 *	Returns available Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array			Response Formats of Service
	 */
	public function getServiceFormats( $serviceName )
	{
		return parent::getServiceFormats( $serviceName );
	}
	
	/**
	 *	Returns available Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array			Parameters of Service
	 */
	public function getServiceParameters( $serviceName )
	{
		if( isset( $this->services['services'][$serviceName]['parameters'] ) )
			return $this->services['services'][$serviceName]['parameters'];
		return array();
	}

	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array			Services in Service Point
	 */
	public function getServices()
	{
		return parent::getServices();
	}
	
	/**
	 *	Returns Syntax of Service Point.
	 *	@access		public
	 *	@return		string			Syntax of Service Point
	 */
	public function getServicesSyntax()
	{
		return parent::getServicesSyntax();
	}
	
	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string			Title of Service Point
	 */
	public function getServicesTitle()
	{
		return parent::getServicesTitle();
	}
}
?>
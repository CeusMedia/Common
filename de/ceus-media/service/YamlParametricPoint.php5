<?php
import( 'de.ceus-media.service.YamlPoint' );
import( 'de.ceus-media.service.interface.Point' );
import( 'de.ceus-media.service.interface.ParametricPoint' );
import( 'de.ceus-media.service.ParameterValidator' );
/**
 *	Service Point for parametric Services with YAML Definition File.
 *	@package		service
 *	@extends		Service_YamlPoint
 *	@implements		Service_Interface_Point
 *	@implements		Service_Interface_ParametricPoint
 *	@uses			ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Service Point for parametric Services with YAML Definition File.
 *	@package		service
 *	@extends		Service_YamlPoint
 *	@implements		Service_Interface_Point
 *	@implements		Service_Interface_ParametricPoint
 *	@uses			ParameterValidator
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
class Service_YamlParametricPoint extends Service_YamlPoint implements Service_Interface_Point, Service_Interface_ParametricPoint
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
}
?>
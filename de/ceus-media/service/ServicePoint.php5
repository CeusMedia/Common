<?php
/**
 *	Interface for Services.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Interface for Services.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
interface ServicePoint
{
	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			Service Definition File Name
	 *	@return		void
	 */
	public function __construct( $fileName );

	/**
	 *	Constructor Method.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@param		string			$responseFormat		Format of Service Response
	 *	@param		array|Object	$requestData		Array or Object of Request Data
	 *	@return		string			Response String of Service	
	 */
	public function callService( $serviceName, $responseFormat = NULL, $requestData = NULL );

	/**
	 *	Returns Services of Service Point.
	 *	@access		public
	 *	@return		array			Services in Service Point
	 */
	public function getServices();

	/**
	 *	Returns Class of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Class of Service
	 */
	public function getServiceClass( $serviceName );
	
	/**
	 *	Returns Description of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		string			Description of Service
	 */
	public function getServiceDescription( $serviceName );

	/**
	 *	Returns available Response Formats of Service.
	 *	@access		public
	 *	@param		string			$serviceName		Name of Service to call 
	 *	@return		array			Response Formats of Service
	 */
	public function getServiceFormats( $serviceName );

	/**
	 *	Returns Title of Service Point.
	 *	@access		public
	 *	@return		string			Title of Service Point
	 */
	public function getServicesTitle();

	/**
	 *	Returns Syntax of Service Point.
	 *	@access		public
	 *	@return		string			Syntax of Service Point
	 */
	public function getServicesSyntax();
}
?>
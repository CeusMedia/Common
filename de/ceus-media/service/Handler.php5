<?php
/**
 *	Interface for Service Handlers.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Interface for Service Handlers.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
abstract class Service_Handler
{
	/**
	 *	Constructor.
	 *	@param		ServicePoint	$servicePoint		Services Class
	 *	@param		array			$availableFormats	Available Response Formats
	 *	@return		void
	 */
	abstract public function __construct( ServicePoint $servicePoint, $availableFormats );

	/**
	 *	Handles Service Call.
	 *	@param		array|Object	$requestData			Request Array or Object
	 *	@param		bool			$serializeException		Flag: serialize Exceptions instead of throwing
	 *	@return		void
	 */
	abstract public function handle( $requestData, $serializeException = false );
}
?>
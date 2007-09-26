<?php
/**
 *	Interface for Service Handlers.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
/**
 *	Interface for Service Handlers.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.2
 */
interface ServiceHandler
{
	/**
	 *	Constructor.
	 *	@param		ServicePoint	$servicePoint		Services Class
	 *	@param		array			$availableFormats	Available Response Formats
	 *	@return		void
	 */
	public function __construct( ServicePoint $servicePoint, $availableFormats );

	/**
	 *	Handles Service Call.
	 *	@param		array|Object	$requestData			Request Array or Object
	 *	@param		bool			$serializeException		Flag: serialize Exceptions instead of throwing
	 *	@return		void
	 */
	public function handle( $requestData, $serializeException = false );
}
?>
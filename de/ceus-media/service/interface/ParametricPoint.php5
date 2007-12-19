<?php
/**
 *	Interface for Services.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Interface for Services.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
interface Service_Interface_ParametricPoint extends Service_Interface_Point
{
	/**
	 *	Returns available Formats of Service.
	 *	@access		public
	 *	@param		string			Service to get Formats of
	 *	@return		array			Formats of this Service
	 */
	public function getServiceParameters( $serviceName );
}
?>
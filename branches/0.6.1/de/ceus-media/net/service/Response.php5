<?php
/**
 *	Basic Response Class for a Service.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
/**
 *	Basic Response Class for a Service.
 *	@package		net.service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.6
 */
class Net_Service_Response
{
	/**
	 *	Return Content as JSON.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getJson( $mixed )
	{
		return json_encode( $mixed );		
	}

	/**
	 *	Return Content as PHP Serial.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getPhp( $mixed )
	{
		return serialize( $mixed );
	}
	
	/**
	 *	Return Content as Base64 String.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getBase64( $mixed )
	{
		return base64_encode( $mixed );
	}
	
	/**
	 *	Return Content as WDDX String.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getWddx( $mixed )
	{
		return wddx_serialize_value( $mixed );
	}
}
?>
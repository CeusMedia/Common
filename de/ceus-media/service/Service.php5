<?php
/**
 *	Basic Class of a Service.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.1
 */
/**
 *	Basic Class of a Service.
 *	@package		service
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			18.06.2007
 *	@version		0.1
 */
class Service
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
	 *	Return Content as gzipped JSON.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getGzipJson( $mixed )
	{
		return gzcompress( json_encode( $mixed ) );		
	}
	
	/**
	 *	Return Content as gzipped PHP Serial.
	 *	@access		protected
	 *	@param		mixed			Content
	 *	@return 	string
	 */
	protected function getGzipPhp( $mixed )
	{
		return gzcompress( serialize( $mixed ) );
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
}
?>
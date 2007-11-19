<?php
/**
 *	Interface of Responses.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
/**
 *	Interface of Responses.
 *	@package		mv2.interface.core
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			05.03.2007
 *	@version		0.1
 */
interface Framework_Krypton_Interface_Core_Response
{
	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$name		Name of Response Header
	 *	@param		mixed		$value 		Value of Response Header
	 *	@return		void
	 */
	public function addHeader( $name, $value );

	/**
	 *	Sends complete Response
	 *	@access		public
	 *	@return		void
	 */
	public function flush();

	/**
	 *	Sets Status of Response.
	 *	@access		public
	 *	@param		string		$status		Status to be set
	 *	@return		void
	 */
	public function setStatus( $status );

	/**
	 *	Writes Data to Response.
	 *	@access		public
	 *	@param		string		$data		Data to be responsed
	 *	@return		void
	 */
	public function write( $data );
}
?>

<?php
/**
 *	Reads a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Reads a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian W�rker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_WDDX_Parser
{
	/**
	 *	Deserializes a wddx packet.
	 *	@access		public
	 *	@return		mixed
	 */
	public function parse( $packet )
	{
		return wddx_deserialize( $packet );
	}
}
?>
<?php
/**
 *	Reads a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Reads a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class XML_WDDX_Parser
{
	/**
	 *	Deserializes a wddx packet.
	 *	@access		public
	 *	@return		mixed
	 */
	public static function parse( $packet )
	{
		return wddx_deserialize( $packet );
	}
}
?>
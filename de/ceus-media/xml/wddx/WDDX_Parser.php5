<?php
/**
 *	Reads a WDDX Packet.
 *	@package		xml
 *	@subpackage		wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Reads a WDDX Packet.
 *	@package		xml
 *	@subpackage		wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class WDDX_Parser
{
	/**	@var		string		Packet name */
	var $_packet;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string 		packet		Packet name
	 *	@return		void
	 */
	function WDDX_Parser( $packet )
	{
		$this->_packet = $packet;
	}

	/**
	 *	Deserializes a wddx packet.
	 *	@access		public
	 *	@return		mixed
	 */
	function parse()
	{
		return wddx_deserialize( $this->_packet );
	}
}
?>
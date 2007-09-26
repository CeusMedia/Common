<?php
/**
 *	Creates a WDDX Packet.
 *	@package	xml
 *	@subpackage	wddx
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
/**
 *	Creates a WDDX Packet.
 *	@package	xml
 *	@subpackage	wddx
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.4
 */
class WDDX_Builder
{
	/**	@var		 int			_pid			Internal packet ID */
	var $_pid;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		packet_name	Name of the packet
	 *	@return		void
	 */
	function WDDX_Builder( $packet_name )
	{
		$this->_pid = wddx_packet_start( $packet_name );
	}

	/**
	 *	Adds a variable/Object to the packet.
	 *	@access		public
	 *	@param		string		name		Name of variable/Object
	 *	@param		string		value		Value of variable/Object
	 *	@return		void
	 */
	function add( $name, $value )
	{
		$$name = $value;
		wddx_add_vars( $this->_pid, $name );
	}

	/**
	 *	Returns packet as WDDX.
	 *	@access		public
	 *	@return		string
	 */
	function build()
	{
		return wddx_packet_end( $this->_pid );
	}
}
?>
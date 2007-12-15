<?php
/**
 *	Creates a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
/**
 *	Creates a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.5
 */
class XML_WDDX_Builder
{
	/**	@var		 int		$pid			Internal packet ID */
	protected $pid;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$packetName		Name of the packet
	 *	@return		void
	 */
	public function __construct( $packetName )
	{
		$this->pid = wddx_packet_start( $packetName );
	}

	/**
	 *	Adds a variable/Object to the packet.
	 *	@access		public
	 *	@param		string		$name			Name of variable/Object
	 *	@param		string		$value			Value of variable/Object
	 *	@return		void
	 */
	public function add( $name, $value )
	{
		$$name = $value;
		wddx_add_vars( $this->pid, $name );
	}

	/**
	 *	Returns packet as WDDX.
	 *	@access		public
	 *	@return		string
	 */
	public function build()
	{
		return wddx_packet_end( $this->pid );
	}
}
?>
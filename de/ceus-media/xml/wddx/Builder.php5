<?php
/**
 *	Creates a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Creates a WDDX Packet.
 *	@package		xml.wddx
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
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
	public function __construct( $packetName = NULL )
	{
		if( $packetName === NULL )
			$this->pid	= wddx_packet_start();
		else
			$this->pid	= wddx_packet_start( $packetName );
	}

	/**
	 *	Adds a Data Object to the packet.
	 *	@access		public
	 *	@param		string		$key			Key of Data Object
	 *	@param		string		$value			Value of Data Object
	 *	@return		bool
	 */
	public function add( $key, $value )
	{
		$$key = $value;
		return wddx_add_vars( $this->pid, $key );
	}

	/**
	 *	Builds WDDX Packet and returns it as String.
	 *	@access		public
	 *	@return		string
	 */
	public function build()
	{
		return wddx_packet_end( $this->pid );
	}
}
?>
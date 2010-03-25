<?php
/**
 *	Creates a WDDX Packet.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		xml.wddx
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Creates a WDDX Packet.
 *	@category		cmClasses
 *	@package		xml.wddx
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
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
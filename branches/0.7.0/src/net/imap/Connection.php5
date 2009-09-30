<?php
/**
 *	Connection Implementation for Accessing a IMAP eMail Server.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		net.imap
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	Conntection Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		0.6
 *	@todo			Code Documentation
 */
class Net_IMAP_Connection
{
	protected $flags	= array();
	protected $folder;
	protected $host;
	protected $port;
	protected $stream;
	
	public function __construct( $host, $port = 143 )
	{
		$this->host		= $host;
		$this->port		= $port;
	}
	
	public function close()
	{
		imap_close( $this->stream );	
	}
	
	public function getAddress( $folder = NULL )
	{
		if( $folder )
			$folder	= $this->folder ? $this->folder."/".$folder : $folder;
		else
			$folder	= $this->folder;
			
		if( !$folder && !$this->hasFlag( OP_HALFOPEN ) )
			$this->setFlag( OP_HALFOPEN );
		$address	= "{".$this->host.":".$this->port."}".$folder;
		return $address;
	}

	public function getStream()
	{
		return $this->stream;
	}
	
	public function hasFlag( $flag )
	{
		if( in_array( $flag, $this->flags ) )
			return TRUE;
	}
	
	public function open( $username, $password, $folder = "" )
	{
		$this->resetFlags();
		$this->folder	= $folder;
		$address		= $this->getAddress();
		$this->stream	= @imap_open( $address, $username, $password );
		if( false === $this->stream )
			throw new Exception( 'Connection could not be established.' );
	}		
	
	public function resetFlags()
	{
		$this->flags	= array();
	}

	public function setFlag( $flag )
	{
		if( !in_array( $flag, $this->flags ) )
			$this->flags[]	= $flag;
	}
}
?>
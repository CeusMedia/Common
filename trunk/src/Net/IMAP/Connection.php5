<?php
/**
 *	Connection Implementation for Accessing a IMAP eMail Server.
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
 *	@package		Net.IMAP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		$Id$
 */
/**
 *	Conntection Implementation for Accessing a IMAP eMail Server.
 *	@category		cmClasses
 *	@package		Net.IMAP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			13.07.2005
 *	@version		$Id$
 *	@todo			Code Documentation
 */
class Net_IMAP_Connection
{
	protected $flags	= array();
	protected $options	= array();
	protected $folder;
	protected $host;
	protected $port;
	protected $stream;
	protected $username;
	protected $password;
	protected $status	= 0;
	
	
	public function __construct( $host, $port = 143, $flags = array() )
	{
		$this->host		= $host;
		$this->port		= $port;
		$this->flags	= $flags;
	}
	
	public function close()
	{
		if( $this->status == 2 )
			imap_close( $this->stream );	
	}
	
	public function getAddress( $folder = NULL )
	{
		if( $folder )
			$folder	= $this->folder ? $this->folder."/".$folder : $folder;
		else
			$folder	= $this->folder;
			
		if( !$folder && !$this->hasOption( OP_HALFOPEN ) )
			$this->setOption( OP_HALFOPEN );
		$flags		= $this->flags ? '/'.join( '/', $this->flags ) : "";
		$address	= "{".$this->host.":".$this->port.$flags."}".$folder;
		return $address;
	}

	public function getStream()
	{
		if( $this->status == 1 )
			$this->open( $this->username, $this->password, $this->folder );
		if( $this->status != 2 )
			throw new RuntimeException( 'Not connected' );
		return $this->stream;
	}
	
	public function hasOption( $option )
	{
		if( in_array( $option, $this->options ) )
			return TRUE;
	}
	
	public function openLazy( $username, $password, $folder = "" )
	{
		$this->username	= $username;
		$this->password	= $password;
		$this->folder	= $folder;
		$this->status	= 1;
	}
	
	public function open( $username, $password, $folder = "" )
	{
		if( $this->status == 2 )
			throw new RuntimeException( 'Connection already established' );
		$this->resetOptions();
		$this->folder	= $folder;
		$address		= $this->getAddress();
		$this->stream	= imap_open( $address, $username, $password );
		if( false === $this->stream )
			throw new RuntimeException( 'Connection could not be established' );
		$this->status	= 2;
	}		
	
	public function resetOptions()
	{
		$this->options	= array();
	}

	public function setOption( $option )
	{
		if( !in_array( $option, $this->options ) )
			$this->options[]	= $option;
	}
}
?>
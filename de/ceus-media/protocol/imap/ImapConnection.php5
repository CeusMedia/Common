<?php
/**
 *	Connection Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 */
/**
 *	Conntection Implementation for Accessing a IMAP eMail Server.
 *	@package	protocol
 *	@subpackage	imap
 *	@author		Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since		13.07.2005
 *	@version		0.4
 *	@todo		Code Documentation
 */
class ImapConnection
{
	var $_stream;
	var $_folder;
	var $_host;
	var $_port;
	var $_flags	= array();
	
	public function __construct( $host, $port = 143, $folder = "" )
	{
		$this->_host	= $host;
		$this->_port	= $port;
		$this->_folder	= $folder;
		$this->resetFlags();
	}
	
	function open( $username, $password )
	{
		$address	= $this->_getAddress();
		$stream	= imap_open( $address, $username, $password );
		if( false === $stream )
			trigger_error( "Connection could not be established", E_USER_ERROR );
		$this->_stream	=& $stream;
	}		
	
	function getStream()
	{
		return $this->_stream;
	}
	
	function close()
	{
		imap_close( $this->_stream );	
	}

	function setFlag( $flag )
	{
		if( !in_array( $flag, $this->_flags ) )
			$this->_flags[]	= $flag;
	}
	
	function resetFlags()
	{
		$this->_flags	= array();
	}
	
	function hasFlag( $flag )
	{
		if( in_array( $flag, $this->_flags ) )
			return $this->_flags[$flag];
	}
	
	function _getAddress()
	{
		if( !$this->_folder && !$this->hasFlag( OP_HALFOPEN ) )
			$this->setFlag( OP_HALFOPEN );
		$address	= "{".$this->_host.":".$this->_port."}".$this->_folder;
		return $address;
	}
	
	function getMailBoxes()
	{
		$folders	= array();
		if( $this->_stream )
			$folders	= imap_listmailbox( $this->_stream, $this->_getAddress(), "*" );
		else
			trigger_error( "Connection has not been opened yet", E_USER_WARNING );
		return $folders;
	}

	function getStatusInfo( $folder = false )
	{
		if( $folder )
			$info	= imap_status( $this->_stream, $this->_getAddress().$folder, SA_ALL );
		else if( $this->_folder )
			$info	= imap_status( $this->_stream, $this->_getAddress(), SA_ALL );
		return $info;
	}
}
?>
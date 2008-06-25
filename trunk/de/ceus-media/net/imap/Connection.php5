<?php
/**
 *	Connection Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			13.07.2005
 *	@version		0.6
 */
/**
 *	Conntection Implementation for Accessing a IMAP eMail Server.
 *	@package		net.imap
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
		$this->stream	= imap_open( $address, $username, $password );
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
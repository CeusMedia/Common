<?php
/**
 *	Sender for Messages via Jabber.
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
 *	@package		net.jabber
 *	@uses			XMPP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			25.04.2008
 *	@version		$Id$
 */
import( 'com.google.code.xmpphp.xmpp' );
/**
 *	Sender for Messages via Jabber.
 *	@category		cmClasses
 *	@package		net.jabber
 *	@uses			XMPP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			25.04.2008
 *	@version		$Id$
 */
class Net_Jabber_MessageSender
{
	/**	@var	bool		$encryption			Flag: use TLS Encryption */
	protected $encryption	= TRUE;
	/**	@var	int			$logLevel			Log Level */
	protected $logLevel		= LOGGING_INFO;
	/**	@var	int			$port				Server Port */
	protected $port			= 5222;
	/**	@var	bool		$printLog			Flag: use Logging */
	protected $printLog		= FALSE;
	/**	@var	string		$resource			Client Resource */
	protected $resource		= "xmpphp";
	/**	@var	XMPP		$xmpp				XMPP Instance */
	protected $xmpp			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$port			Server Port
	 *	@param		bool		$encryption		Flag: use TLS Encryption
	 *	@param		bool		$printLog		Flag: use Logging
	 *	@param		int			$logLevel		Log Level (LOGGING_ERROR|LOGGING_WARNING|LOGGING_INFO|LOGGING_DEBUG|LOGGING_VERBOSE)
	 *	@return		void
	 */
	public function __construct( $port = NULL, $encryption = NULL, $printLog = NULL, $logLevel = NULL )
	{
		if( $port !== NULL )
			$this->setPort( $port );
		if( $encryption !== NULL )
			$this->setEncryption( $encryption );
		if( $printLog !== NULL )
			$this->setPrintLog( $printLog );
		if( $logLevel !== NULL )
			$this->setLogLevel( $logLevel );
	}

	/**
	 *	Destructor, closes Connection.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		$this->disconnect();
	}
	
	/**
	 *	Establishs Connection to XMPP Server.
	 *	@access		public
	 *	@param		string		$username		Username of Sender
	 *	@param		string		$password		Password of Sender
	 *	@param		string		$server			XMPP Server of Sender
	 *	@param		int			$port			Port of XMPP Server
	 *	@return		void
	 */
	public function connect( $username, $password, $server, $port = NULL )
	{
		$port	= $port ? $port : $this->port;
		$this->xmpp		= new XMPP( $server, $port, $username, $password, $this->resource, $server, $this->printLog, $this->logLevel );
		$this->xmpp->use_encyption	= $this->encryption;
		$this->xmpp->connect();
		$this->xmpp->processUntil( 'session_start' );
	}
	
	/**
	 *	Closes Connection if still open.
	 *	@access		public
	 *	@return		bool
	 */
	public function disconnect()
	{
		if( $this->xmpp )
		{
			if( $this->printLog )
				echo $this->xmpp->log->printout();
			$this->xmpp->disconnect();
			unset( $this->xmpp );
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Sends Message to set Receiver.
	 *	@access		public
	 *	@param		string		$message		Message to send to Receiver
	 *	@return		void
	 */
	public function sendMessage( $message )
	{
		if( !$this->receiver )
			throw new RuntimeException( 'No Receiver set.' );
		$this->sendMessageTo( $message, $this->receiver );
	}

	/**
	 *	Sends Message to a Receiver.
	 *	@access		public
	 *	@param		string		$message		Message to send to Receiver
	 *	@param		string		$receiver		JID of Receiver
	 *	@return		void
	 */
	public function sendMessageTo( $message, $receiver )
	{
		if( !$this->xmpp )
			throw new RuntimeException( 'Not connected to Server.' );
		$this->xmpp->message( $receiver, $message );	
	}

	/**
	 *	Sets Encryption
	 *	@access		public
	 *	@param		bool		$bool			Flag: set Encryption
	 *	@return		void
	 */
	public function setEncryption( $bool )
	{
		$this->encryption	= $bool;
	}
	
	/**
	 *	Sets Log Level.
	 *	@access		public
	 *	@param		int			$logLevel		Log Level (LOGGING_ERROR|LOGGING_WARNING|LOGGING_INFO|LOGGING_DEBUG|LOGGING_VERBOSE)
	 *	@return		void
	 */
	public function setLogLevel( $level )
	{
		$this->logLevel	= $level;
	}
	
	/**
	 *	Sets Port for XMPP Server of Sender.
	 *	@access		public
	 *	@param		int			$port			XMPP Server Port
	 *	@return		void
	 */
	public function setPort( $port )
	{
		$this->port	= $port;
	}

	/**
	 *	Sets Logging.
	 *	@access		public
	 *	@param		bool		$bool			Flag: use Logging
	 *	@return		void
	 */
	public function setPrintLog( $bool )
	{
		$this->printLog	= $bool;
	}

	/**
	 *	Sets Receiver by its JID.
	 *	@access		public
	 *	@param		string		$receiver		JID of Receiver
	 *	@return		void
	 */
	public function setReceiver( $receiver )
	{
		$this->receiver	= $receiver;
	}
	
	/**
	 *	Sets Client Resource Name.
	 *	@access		public
	 *	@param		string		$resource		Client Resource Name
	 *	@return		void
	 */
	public function setResource( $resource )
	{
		$this->resource	= $resource;
	}
}
?>
<?php
/**
 *	Sender for Messages via Jabber.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			25.04.2008
 */

namespace CeusMedia\Common\Net\XMPP;

use CeusMedia\Common\Net\XMPP\XMPPHP\Log;
use CeusMedia\Common\Net\XMPP\XMPPHP\XMPP;
use RuntimeException;

/**
 *	Sender for Messages via Jabber.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			25.04.2008
 */
class MessageSender
{
	/**	@var	bool		$encryption				Flag: use TLS Encryption */
	protected $encryption	= TRUE;

	/**	@var	int			$logLevel				Log Level */
	protected $logLevel		= Log::LEVEL_INFO;

	/**	@var	int			$port					Server Port */
	protected $port			= 5222;

	/**	@var	bool		$printLog				Flag: use Logging */
	protected $printLog		= FALSE;

	/**	@var	string		$receiver				Receiver JID */
	protected $receiver		= NULL;

	/**	@var	string		$resource				??? */
	protected $resource		= "xmpphp";

	/**	@var	XMPP		$xmpp					XMPP Instance */
	public $xmpp			= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$port			Server Port
	 *	@param		bool		$encryption		Flag: use TLS Encryption
	 *	@param		bool		$printLog		Flag: use Logging
	 *	@param		int			$logLevel		Log Level (Log::LEVEL_ERROR|Log::LEVEL_WARNING|Log::LEVEL_INFO|Log::LEVEL_DEBUG|Log::LEVEL_VERBOSE)
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
	 *	@param		JID			$sender			JID of sender
	 *	@param		string		$password		Password of Sender
	 *	@param		int			$port			Port of XMPP Server
	 *	@return		void
	 */
	public function connect( JID $sender, $password )
	{
		$this->xmpp		= new XMPP(
			$sender->getDomain(),
			$this->port,
			$sender->getNode(),
			$password,
			$sender->getResource() ? $sender->getResource() : $this->resource,
			$sender->getDomain(),
			$this->printLog,
			$this->logLevel
		);
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
			$this->xmpp = NULL;
			return TRUE;
		}
		return FALSE;
	}

	/**
	 *	Sends Message to set Receiver.
	 *	@access		public
	 *	@param		string		$message		Message to send to Receiver
	 *	@return		void
	 *	@throws		RuntimeException			if no receiver has been set
	 */
	public function sendMessage( $message )
	{
		if( !$this->receiver )
			throw new RuntimeException( 'No Receiver set.' );
		$this->sendMessageTo( $message, $this->receiver->get() );
	}

	/**
	 *	Sends Message to a Receiver.
	 *	@access		public
	 *	@param		string		$message		Message to send to Receiver
	 *	@param		string		$receiver		JID of Receiver
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is not established
	 */
	public function sendMessageTo( $message, $receiver )
	{
		if( is_string( $receiver ) )
			$receiver	= new JID( $receiver );
		if( !$this->xmpp )
			throw new RuntimeException( 'Not connected to Server.' );
		$this->xmpp->message( $receiver->get(), $message );
	}

	/**
	 *	Sets Encryption
	 *	@access		public
	 *	@param		bool		$bool			Flag: set Encryption
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setEncryption( $bool )
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->encryption	= $bool;
	}

	/**
	 *	Sets Log Level.
	 *	@access		public
	 *	@param		int			$logLevel		Log Level (Log::LEVEL_ERROR|Log::LEVEL_WARNING|Log::LEVEL_INFO|Log::LEVEL_DEBUG|Log::LEVEL_VERBOSE)
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setLogLevel( $level )
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->logLevel	= $level;
	}

	/**
	 *	Sets Port for XMPP Server of Sender.
	 *	@access		public
	 *	@param		int			$port			XMPP Server Port
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setPort( $port )
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->port	= $port;
	}

	/**
	 *	Sets Logging.
	 *	@access		public
	 *	@param		bool		$bool			Flag: use Logging
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setPrintLog( $bool )
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
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
		if( is_string( $receiver ) )
			$receiver	= new JID( $receiver );
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

	public function setStatus( $status ){
		$this->xmpp->presence( $status );
	}
}

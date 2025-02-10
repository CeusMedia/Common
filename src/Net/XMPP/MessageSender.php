<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Sender for Messages via Jabber.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_XMPP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
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
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MessageSender
{
	/**	@var	bool			$encryption				Flag: use TLS Encryption */
	protected $encryption		= TRUE;

	/**	@var	int				$logLevel				Log Level */
	protected $logLevel			= Log::LEVEL_INFO;

	/**	@var	int				$port					Server Port */
	protected $port				= 5222;

	/**	@var	bool			$printLog				Flag: use Logging */
	protected $printLog			= FALSE;

	/**	@var	JID|string|NULL	$receiver				Receiver JID */
	protected $receiver			= NULL;

	/**	@var	string			$resource				??? */
	protected $resource			= "xmpphp";

	/**	@var	XMPP|NULL		$xmpp					XMPP Instance */
	public $xmpp				= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int|NULL		$port			Server Port
	 *	@param		bool|NULL		$encryption		Flag: use TLS Encryption
	 *	@param		bool|NULL		$printLog		Flag: use Logging
	 *	@param		int|NULL		$logLevel		Log Level (Log::LEVEL_ERROR|Log::LEVEL_WARNING|Log::LEVEL_INFO|Log::LEVEL_DEBUG|Log::LEVEL_VERBOSE)
	 *	@return		void
	 */
	public function __construct( ?int $port = NULL, ?bool $encryption = NULL, ?bool $printLog = NULL, ?int $logLevel = NULL )
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
	 *	Establishes Connection to XMPP Server.
	 *	@access		public
	 *	@param		JID			$sender			JID of sender
	 *	@param		string		$password		Password of Sender
	 *	@return		void
	 *	@throws		XMPPHP\Exception
	 */
	public function connect( JID $sender, string $password )
	{
		$this->xmpp		= new XMPP(
			$sender->getDomain(),
			$this->port,
			$sender->getNode(),
			$password,
			$sender->getResource() ?: $this->resource,
			$sender->getDomain(),
			$this->printLog,
			$this->logLevel
		);
		$this->xmpp->useEncryption( $this->encryption );
		$this->xmpp->connect();
		$this->xmpp->processUntil( 'session_start' );
	}

	/**
	 *	Closes Connection if still open.
	 *	@access		public
	 *	@return		bool
	 */
	public function disconnect(): bool
	{
		if( $this->xmpp ){
			if( $this->printLog )
				$this->xmpp->getLog()->printout();
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
	public function sendMessage( string $message )
	{
		if( NULL === $this->receiver )
			throw new RuntimeException( 'No Receiver set.' );
		$this->sendMessageTo( $message, $this->receiver->get() );
	}

	/**
	 *	Sends Message to a Receiver.
	 *	@access		public
	 *	@param		string			$message		Message to send to Receiver
	 *	@param		JID|string		$receiver		JID of Receiver
	 *	@return		void
	 *	@throws		RuntimeException			if XMPP connection is not established
	 */
	public function sendMessageTo( string $message, $receiver )
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
	 *	@return		self
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setEncryption( bool $bool ): self
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->encryption	= $bool;
		return $this;
	}

	/**
	 *	Sets Log Level.
	 *	@access		public
	 *	@param		int			$level		Log Level (Log::LEVEL_ERROR|Log::LEVEL_WARNING|Log::LEVEL_INFO|Log::LEVEL_DEBUG|Log::LEVEL_VERBOSE)
	 *	@return		self
	 *	@throws		RuntimeException		if XMPP connection is already established
	 */
	public function setLogLevel( int $level ): self
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->logLevel	= $level;
		return $this;
	}

	/**
	 *	Sets Port for XMPP Server of Sender.
	 *	@access		public
	 *	@param		int			$port			XMPP Server Port
	 *	@return		self
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setPort( int $port ): self
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->port	= $port;
		return $this;
	}

	/**
	 *	Sets Logging.
	 *	@access		public
	 *	@param		bool		$bool			Flag: use Logging
	 *	@return		self
	 *	@throws		RuntimeException			if XMPP connection is already established
	 */
	public function setPrintLog( bool $bool ): self
	{
		if( $this->xmpp )
			throw new RuntimeException( 'Already connected' );
		$this->printLog	= $bool;
		return $this;
	}

	/**
	 *	Sets Receiver by its JID.
	 *	@access		public
	 *	@param		JID|string		$receiver		JID of Receiver
	 *	@return		self
	 */
	public function setReceiver( $receiver ): self
	{
		if( is_string( $receiver ) )
			$receiver	= new JID( $receiver );
		$this->receiver	= $receiver;
		return $this;
	}

	/**
	 *	Sets Client Resource Name.
	 *	@access		public
	 *	@param		string		$resource		Client Resource Name
	 *	@return		self
	 */
	public function setResource( string $resource ): self
	{
		$this->resource	= $resource;
		return $this;
	}

	/**
	 *	@param		string		$status
	 *	@return		self
	 */
	public function setStatus( string $status ): self
	{
		$this->xmpp->presence( $status );
		return $this;
	}
}

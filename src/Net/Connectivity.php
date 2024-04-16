<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	...
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net;

use RangeException;

/**
 *	...
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Connectivity
{
	public const STATUS_UNKNOWN	= 0;
	public const STATUS_OFFLINE	= 1;
	public const STATUS_ONLINE		= 2;

	public const METHOD_SOCKET		= 0;
	public const METHOD_PING		= 1;

	public const METHODS			= [
		self::METHOD_SOCKET,
		self::METHOD_PING,
	];

	protected int $status		= self::STATUS_UNKNOWN;
	protected int $method		= self::METHOD_SOCKET;
	protected string $target		= 'google.com';
	protected mixed $callbackOnChange;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct()
	{
	}

	/**
	 *	...
 	 *	Executes callback function by if set and status has changed.
	 *	@access		public
	 *	@return		void
	 *	@throws		RangeException			if given method is unsupported
	 */
	public function check(): void
	{
		$currentStatus	= $this->status;
		switch( $this->method ){
			case self::METHOD_SOCKET:
				$this->checkUsingSocket();
				break;
			case self::METHOD_PING:
				$this->checkUsingSystemPing();
				break;
		}
		if( $this->status !== $currentStatus ){
			if( $this->callbackOnChange ){
				call_user_func_array( $this->callbackOnChange, [$this->status] );
			}
		}
	}

	/**
	 *	Check connectivity using a socket connection.
	 *	Sets status depending on result.
	 *	@access		public
	 *	@return		void
	 */
	public function checkUsingSocket(): void
	{
		$conn	= @fsockopen( 'google.com', 443);
		$this->status = $conn ? self::STATUS_ONLINE : self::STATUS_OFFLINE;
		fclose( $conn );
	}

	/**
	 *	Check connectivity using ping via system call.
	 *	Sets status depending on result.
	 *	@access		public
	 *	@return		void
	 */
	public function checkUsingSystemPing(): void
	{
		$response = NULL;
		@exec( "ping -c 1 google.com 2>&1 1> /dev/null", $response, $code );
		$this->status = $code === 0 ? self::STATUS_ONLINE : self::STATUS_OFFLINE;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		bool		$force		Flag: evaluate connectivity instead if returning latest status
	 *	@return		boolean
	 */
	public function isOnline( bool $force = FALSE ): bool
	{
		if( $this->status === self::STATUS_UNKNOWN || $force )
			$this->check();
		return $this->status === self::STATUS_ONLINE;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		bool		$force		Flag: evaluate connectivity instead if returning latest status
	 *	@return		boolean
	 */
	public function isOffline( bool $force = FALSE ): bool
	{
		return !$this->isOnline( $force );
	}

	/**
	 *	Register callback function to be executed after status has changed.
	 *	@access		public
	 *	@param		mixed		$callback	Function to be executed after status has changed
	 *	@return		self					for method chaining
	 *  @noinspection						PhpMissingParamTypeInspection
	 */
	public function setCallbackOnChange( $callback ): self
	{
		$this->callbackOnChange = $callback;
		return $this;
	}

	/**
	 *	...
	 *	@access 	public
	 *	@param		integer		$method			Method to use for checking
	 *	@param		boolean		$resetStatus	Flag: resets status after method has been changed
	 *	@return 	self						for method chaining
	 */
	public function setMethod( int $method, bool $resetStatus = TRUE ): self
	{
		if( !in_array( $method, self::METHODS ) )
			throw new RangeException( 'Invalid method' );

		if( $this->method !== $method ){
			$this->method	= $method;
			$this->status	= $resetStatus ? self::STATUS_UNKNOWN : $this->status;
		}
		return $this;
	}

	/**
	 *	Set target (domain or IP) to check connectivity against.
	 *	@access 	public
	 *	@param		string		$domainOrIp		Method to use for checking
	 *	@param		boolean		$resetStatus	Flag: resets status after method has been changed
	 *	@return 	self						for method
	 */
	public function setTarget( string $domainOrIp, bool $resetStatus = TRUE ): self
	{
		if( $this->target !== $domainOrIp ){
			$this->target	= $domainOrIp;
			$this->status	= $resetStatus ? self::STATUS_UNKNOWN : $this->status;
		}
		return $this;
	}
}

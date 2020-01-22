<?php
/**
 *	Reader for Contents from the Net.
 *
 *	Copyright (c) 2007-2020 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Reader for Contents from the Net.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Net_Connectivity{

	const STATUS_UNKNOWN	= 0;
	const STATUS_OFFLINE	= 1;
	const STATUS_ONLINE		= 2;

	const METHOD_SOCKET		= 0;
	const METHOD_PING		= 1;

	protected $status		= 0;
	protected $method		= 0;
	protected $target		= 'google.com';
	protected $callbackOnChange;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct(){
	}

	/**
	 *	...
 	 *	Executes callback function by if set and status has changed.
	 *	@access		public
	 *	@param		integer		$method		Method to use for checking
	 *	@return		void
	 *	@throws		\RangeException			if given method is unsupported
	 */
	public function check( $method = NULL ){
		$method			= $this->method;
		if( $method && $this->validateMethod( $method ) )
			$method		= $method;
		$currentStatus	= $this->status;
		switch( $method ){
			case self::METHOD_SOCKET:
				$this->checkUsingSocket();
				break;
			case self::METHOD_PING:
				$this->checkUsingSystemPing();
				break;
		}
		if( $this->status !== $currentStatus ){
			if( $this->callbackOnChange ){
				call_user_func_array( $this->callbackOnChange, array( $this->status ) );
			}
		}
	}

	/**
	 *	Check connectivity using a socket connection.
	 *	Sets status depending on result.
	 *	@access		public
	 *	@return		void
	 */
	public function checkUsingSocket(){
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
	public function checkUsingSystemPing(){
		$response = NULL;
		@exec( "ping -c 1 google.com 2>&1 1> /dev/null", $response, $code );
//		@system( "ping -c 1 1111google.com &2>1 &1>/dev/null", $code );
		if( $code == 0 )
			$this->status = self::STATUS_ONLINE;
		else
			$this->status = self::STATUS_OFFLINE;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		integer		$force		Flag: evaluate connectivity instead if returning latest status
	 *	@return		boolean
	 */
	public function isOnline( $force = FALSE ){
		if( $this->status === self::STATUS_UNKNOWN || $force )
			$this->check();
		return $this->status === self::STATUS_ONLINE;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		integer		$force		Flag: evaluate connectivity instead if returning latest status
	 *	@return		boolean
	 */
	public function isOffline( $force = FALSE ){
		if( $this->status === self::STATUS_UNKNOWN || $force )
			$this->check();
		return $this->status === self::STATUS_OFFLINE;
	}

	/**
	 *	Register callback function to be executed after status has changed.
	 *	@access		public
	 * 	@param		callback	$callback	Function to be executed after statush has changed
	 *	@return 	self					for chainability
	 */
	public function setCallbackOnChange( $callback ){
		$this->callbackOnChange = $callback;
		return $this;
	}

	/**
	 *	...
	 *	@access 	public
	 *	@param		integer		$method			Method to use for checking
	 *	@param	 	boolean		$resetStatus	Flag: resets status after method has been changed
	 *	@return 	self						for chainability
	 */
	public function setMethod( $method, $resetStatus = TRUE ){
		$this->validateMethod( $method );
		if( $this->method !== $method ){
			$this->method	= $method;
			if( $resetStatus )
				$this->status	= self::STATUS_UNKNOWN;
		}
		return $this;
	}

	/**
	 *	Set target (domain or IP) to check connectivity against.
	 *	@access 	public
	 *	@param		integer		$method			Method to use for checking
	 *	@param	 	boolean		$resetStatus	Flag: resets status after method has been changed
	 *	@return 	self						for chainability
	 */
	public function setTarget( $domainOrIp, $resetStatus = TRUE ){
		if( $this->target !== $domainOrIp ){
			$this->target	= $domainOrIp;
			if( $resetStatus )
				$this->status	= self::STATUS_UNKNOWN;
		}
		return $this;
	}

	/**
	 *	Validate given method.
	 *	@acess		protected
	 *	@param		integer		Method to validate
	 *	@return		integer		Method after validation
	 */
	protected function validateMethod( $method ){
		if( !is_int( $method ) )
			throw new \InvalidArgumentException( 'Method must be integer' );
		if( !in_array( $method, array( self::METHOD_SOCKET, self::METHOD_PING ) ) )
			throw new \RangeException( 'Invalid method' );
		return $method;
	}
}

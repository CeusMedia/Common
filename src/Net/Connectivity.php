<?php
/**
 *	Reader for Contents from the Net.
 *
 *	Copyright (c) 2007-2019 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2019 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
/**
 *	Reader for Contents from the Net.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2019 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Net_Connectivity{

	const STATUS_UNKNOWN	= 0;
	const STATUS_OFFLINE	= 1;
	const STATUS_ONLINE		= 2;

	const METHOD_SOCKET		= 0;
	const METHOD_PING		= 1;

	protected $status;

	protected $callbackOnChange;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __construct(){
		$this->check();
	}

	/**
	 *	...
 	 *	Executes callback function by if set and status has changed.
	 *	@access		public
	 *	@param		integer		$method		Method to use for checking
	 *	@return		void
	 *	@throws		\RangeException			if given method is unsupported
	 */
	public function check( $method = self::METHOD_SOCKET ){
		$currentStatus	= $this->status;
		switch( $method ){
			case self::METHOD_SOCKET:
				$this->checkUsingSocket();
				break;
			case self::METHOD_PING:
				$this->checkUsingSystemPing();
				break;
			default:
				throw new \RangeException( 'Unsupported method: '.$method );
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
		system( "ping -c 1 google.com", $response );
		if( $response == 0 )
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
	 *	@return 	void
	 */
	public function onChange( $callback ){
		$this->callbackOnChange = $callback;
	}
}

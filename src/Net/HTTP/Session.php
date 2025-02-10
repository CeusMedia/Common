<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Session Management.
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
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\ADT\Collection\Dictionary;

/**
 *	Session Management.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Session extends Dictionary
{
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$sessionName		Name of Session ID
	 *	@param		string|NULL		$domain				Domain to set cookie for
	 *	@return		void
	 */
	public function __construct( string $sessionName = "sid", ?string $domain = NULL )
	{
		parent::__construct();
		//  set session cookie name
		session_name( $sessionName );
		//  a domain has been specified
		if( strlen( trim( $domain ) ) )
			//  set cookie domain
			ini_set( 'session.cookie_domain', trim( strtolower( $domain ) ) );
		//  start cookie handler
		@session_start();
		$this->pairs =& $_SESSION;
	}

	/**
	 *	Destructor.
	 *	@access		public
	 *	@return		void
	 */
	public function __destruct()
	{
		session_write_close();
	}

	/**
	 *	Clears Session.
	 *	@access		public
	 *	@return		void
	 */
	public function clear()
	{
		$this->pairs	= [];
#		foreach( $this->pairs as $key => $value )
#			unset( $this->pairs[$key] );
	}

	/**
	 *	Returns current Session ID.
	 *	@access		public
	 *	@return		string
	 */
	public function getSessionID(): string
	{
		return session_id();
	}

	/**
	 *	Returns current Session Name.
	 *	@access		public
	 *	@return		string
	 */
	public function getSessionName(): string
	{
		return session_name();
	}
}

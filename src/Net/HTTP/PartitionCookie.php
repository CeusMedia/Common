<?php
/**
 *	Partitioned Cookie Management.
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
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.08.2005
 */
/**
 *	Partitioned Cookie Management.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			11.08.2005
 */
class Net_HTTP_PartitionCookie extends Net_HTTP_Cookie
{
	/**	@var		string		$partition		Name of partition in cookie */
	protected $partition;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function __construct ( $partition, $path = "/", $domain = NULL, $secure = FALSE, $httpOnly = FALSE )
	{
		$this->partition	= $partition;
		$this->setPath( $path );
		$this->setDomain( $domain );
		$this->setSecure( $secure );
		$this->setHttpOnly( $httpOnly );
		$pairs	= array();
		if( isset( $_COOKIE[$partition] ) )
			$this->data	= json_decode( $_COOKIE[$partition], TRUE );
	}

	/**
	 *	Returns a Cookie by its key.
	 *	@access		public
	 *	@param		string		$key			Key of Cookie
	 *	@return		mixed
	 */
	public function get( $key )
	{
		$key	= str_replace( ".", "_", $key );
		if( isset( $this->data[$key] ) )
			return $this->data[$key];
		return NULL;
	}

	/**
	 *	Removes a cookie part.
	 *	@access		public
	 *	@param		string		$key			Key of cookie part
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function remove( $key, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL )
	{
		$key	= str_replace( ".", "_", $key );
		if( !isset( $this->data[$key] ) )
			return;
		unset( $this->data[$key] );
		$this->save( $path, $domain, $secure, $httpOnly );
	}

	/**
	 *	Sets a Cookie to this PartitionCookie.
	 *	@access		public
	 *	@param		string		$key			Key of Cookie
	 *	@param		string		$value			Value of Cookie
	 *	@param		integer		$expires		EOL as UNIX timestamp
	 *	@param		string		$path			Path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function set( $key, $value, $expires = 0, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL )
	{
		$key	= str_replace( ".", "_", $key );
		$this->data[$key] = $value;
		$this->save( $expires, $path, $domain, $secure, $httpOnly );
	}

	//  --  PROTECTED  --  //

	/**
	 *	Saves PartitionCookie by sending to Browser.
	 *	@access		protected
	 *	@param		integer		$expires		EOL as UNIX timestamp
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 *	@return		void
	 */
	protected function save( $expires = 0, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL )
	{
		$value		= json_encode( $this->data );
		$expires	= $expires ? time() + $expires : $expires;
		$path		= $path !== NULL ? $path : $this->path;
		$domain		= $domain !== NULL ? $domain : $this->domain;
		$secure		= $secure !== NULL ? $secure : $this->secure;
		$httpOnly	= $httpOnly !== NULL ? $httpOnly : $this->httpOnly;
		setCookie( $this->partition, $value, $expires, $path, $domain, $secure, $httpOnly );
	}
}

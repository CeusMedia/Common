<?php
/**
 *	Cookie Management.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			01.07.2005
 *	@version		$Id$
 */
/**
 *	Cookie Management.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2018 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			01.07.2005
 *	@version		$Id$
 */
class Net_HTTP_Cookie
{
	/**	@var		array		$data			Reference to cookie data */
	protected $data;
	/** @var		string		$path			Default path of cookie */
	protected $path;
	/** @var		string		$domain			Domain of cookie */
	protected $domain			= NULL;
	/** @var		boolean		$secure			Flag: only with secured HTTPS connection */
	protected $secure			= FALSE;
	/** @var		boolean		$httpOnly		Flag: allow access via HTTP protocol only */
	protected $httpOnly			= FALSE;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function __construct( $path = NULL, $domain = NULL, $secure = FALSE, $httpOnly = FALSE )
	{
		$this->data		=& $_COOKIE;
		$this->setPath( $path );
		$this->setDomain( $domain );
		$this->setSecure( $secure );
		$this->setHttpOnly( $httpOnly );
	}

	/**
	 *	Returns a setting by its key name.
	 *	@access		public
	 *	@param		string		$key			Key name of cookie
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
	 *	Returns all cookies for this path and domain.
	 *	@access		public
	 *	@return		array
	 */
	public function getAll()
	{
		return $this->data;
	}

	/**
	 *	Indicates whehter a cookie is set by its name.
	 *	@access		public
	 *	@param		string		$key			Key name of cookie
	 *	@return		boolean
	 */
	public function has( $key )
	{
		$key	= str_replace( ".", "_", $key );
		return isset( $this->data[$key] );
	}

	/**
	 *	Removes a cookie.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 */
	public function remove( $key, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL )
	{
		$key		= str_replace( ".", "_", $key );
		$expires	= time() - 1;
		$path		= $path ? $path : $this->path;
		$domain		= $domain !== NULL ? $domain : $this->domain;
		$secure		= $secure !== NULL ? $secure : $this->secure;
		$httpOnly	= $httpOnly !== NULL ? $httpOnly : $this->httpOnly;
		if( !isset( $this->data[$key] ) )
			return FALSE;
		unset( $this->data[$key] );
		setcookie( $key, "", $expires, $path, $domain, $secure, $httpOnly );
		return TRUE;
	}

	/**
	 *	Writes a setting to Cookie.
	 *	@access		public
	 *	@param		string		$key			Key name of setting
	 *	@param		string		$value			Value of setting
	 *	@param		integer		$expires		EOL as UNIX timestamp
	 *	@param		string		$path			Default path of cookie
	 *	@param		string		$domain			Domain of cookie
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 */
	public function set( $key, $value, $expires = 0, $path = NULL, $domain = NULL, $secure = NULL, $httpOnly = NULL )
	{
		$key		= str_replace( ".", "_", $key );
		$expires	= $expires ? time() + $expires : $expires;
		$path		= $path !== NULL ? $path : $this->path;
		$domain		= $domain !== NULL ? $domain : $this->domain;
		$secure		= $secure !== NULL ? $secure : $this->secure;
		$httpOnly	= $httpOnly !== NULL ? $httpOnly : $this->httpOnly;
		$this->data[$key]	=& $value;
		return setcookie( $key, $value, $expires, $path, $domain, $secure, $httpOnly );
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		string		$domain			Domain of cookie
	 *	@return		void
	 */
	public function setDomain( $domain )
	{
		$this->domain	= $domain;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		boolean		$httponly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function setHttpOnly( $boolean )
	{
		$this->httpOnly	= $boolean;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		string		$path			Default path of cookie
	 *	@return		void
	 */
	public function setPath( $path )
	{
		$this->path = $path;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@return		void
	 */
	public function setSecure( $boolean )
	{
		$this->secure = $boolean;
	}
}

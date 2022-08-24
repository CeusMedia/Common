<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Cookie Management.
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
 */

namespace CeusMedia\Common\Net\HTTP;

/**
 *	Cookie Management.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Cookie
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
	 *	@param		string|NULL		$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean			$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean			$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function __construct( ?string $path = NULL, ?string $domain = NULL, bool $secure = FALSE, bool $httpOnly = FALSE )
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
	public function get( string $key )
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
	public function getAll(): array
	{
		return $this->data;
	}

	/**
	 *	Indicates whether a cookie is set by its name.
	 *	@access		public
	 *	@param		string		$key			Key name of cookie
	 *	@return		boolean
	 */
	public function has( string $key ): bool
	{
		$key	= str_replace( ".", "_", $key );
		return isset( $this->data[$key] );
	}

	/**
	 *	Removes a cookie.
	 *	@access		public
	 *	@param		string			$key			Key name of setting
	 *	@param		string|NULL		$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean|NULL	$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean|NULL	$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 */
	public function remove( string $key, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		$key		= str_replace( ".", "_", $key );
		if( !isset( $this->data[$key] ) )
			return FALSE;
		unset( $this->data[$key] );
		return setcookie( $key, '', [
			'expires'		=> time() - 1,
			'path'			=> $path ?? $this->path,
			'domain'		=> $domain ?? $this->domain,
			'secure'		=> $secure ?? $this->secure,
			'httponly'		=> $httpOnly ?? $this->httpOnly
		]);
	}

	/**
	 *	Writes a setting to Cookie.
	 *	@access		public
	 *	@param		string			$key			Key name of setting
	 *	@param		mixed			$value			Value of setting
	 *	@param		integer			$expires		EOL as UNIX timestamp
	 *	@param		string|NULL		$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean|NULL	$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean|NULL	$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 */
	public function set( string $key, $value, int $expires = 0, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		$key		= str_replace( ".", "_", $key );
		$this->data[$key]	=& $value;
		return setcookie( $key, $value, [
			'expires'		=> $expires ? time() + $expires : $expires,
			'path'			=> $path ?? $this->path,
			'domain'		=> $domain ?? $this->domain,
			'secure'		=> $secure ?? $this->secure,
			'httponly'		=> $httpOnly ?? $this->httpOnly
		]);
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		string		$domain			Domain of cookie
	 *	@return		self
	 */
	public function setDomain( string $domain ): self
	{
		$this->domain	= $domain;
		return $this;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		boolean		$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		self
	 */
	public function setHttpOnly( bool $httpOnly ): self
	{
		$this->httpOnly	= $httpOnly;
		return $this;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		string		$path			Default path of cookie
	 *	@return		self
	 */
	public function setPath( string $path ): self
	{
		$this->path = $path;
		return $this;
	}

	/**
	 *	Set cookie domain.
	 *	@access		public
	 *	@param		boolean		$secure			Flag: only with secured HTTPS connection
	 *	@return		self
	 */
	public function setSecure( bool $secure ): self
	{
		$this->secure = $secure;
		return $this;
	}
}

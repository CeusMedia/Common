<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Partitioned Cookie Management.
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

/**
 *	Partitioned Cookie Management.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class PartitionCookie extends Cookie
{
	/**	@var		string		$partition		Name of partition in cookie */
	protected $partition;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string			$partition		...
	 *	@param		string			$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean			$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean			$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		void
	 */
	public function __construct ( string $partition, string $path = "/", ?string $domain = NULL, bool $secure = FALSE, bool $httpOnly = FALSE )
	{
		parent::__construct();
		$this->partition	= $partition;
		$this->setPath( $path );
		$this->setDomain( $domain );
		$this->setSecure( $secure );
		$this->setHttpOnly( $httpOnly );
		if( isset( $_COOKIE[$partition] ) )
			$this->data	= json_decode( $_COOKIE[$partition], TRUE );
	}

	/**
	 *	Returns a Cookie by its key.
	 *	@access		public
	 *	@param		string		$key			Key of Cookie
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
	 *	Removes a cookie part.
	 *	@access		public
	 *	@param		string			$key			Key of cookie part
	 *	@param		string|NULL		$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean|NULL	$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean|NULL	$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		bool
	 */
	public function remove( string $key, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		$key	= str_replace( ".", "_", $key );
		if( !isset( $this->data[$key] ) )
			return FALSE;
		unset( $this->data[$key] );
		return $this->save( 0, $path, $domain, $secure, $httpOnly );
	}

	/**
	 *	Sets a Cookie to this PartitionCookie.
	 *	@access		public
	 *	@param		string			$key			Key of Cookie
	 *	@param		mixed			$value			Value of Cookie
	 *	@param		integer			$expires		EOL as UNIX timestamp
	 *	@param		string|NULL		$path			Path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean|NULL	$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean|NULL	$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		bool
	 */
	public function set( string $key, $value, int $expires = 0, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		$key	= str_replace( ".", "_", $key );
		$this->data[$key] = $value;
		return $this->save( $expires, $path, $domain, $secure, $httpOnly );
	}

	//  --  PROTECTED  --  //

	/**
	 *	Saves PartitionCookie by sending to Browser.
	 *	@access		protected
	 *	@param		integer			$expires		EOL as UNIX timestamp
	 *	@param		string|NULL		$path			Default path of cookie
	 *	@param		string|NULL		$domain			Domain of cookie
	 *	@param		boolean|NULL	$secure			Flag: only with secured HTTPS connection
	 *	@param		boolean|NULL	$httpOnly		Flag: allow access via HTTP protocol only
	 *	@return		boolean
	 */
	protected function save( int $expires = 0, ?string $path = NULL, ?string $domain = NULL, ?bool $secure = NULL, ?bool $httpOnly = NULL ): bool
	{
		return setcookie( $this->partition, json_encode( $this->data ), [
			'expires'		=> $expires ? time() + $expires : $expires,
			'path'			=> $path ?? $this->path,
			'domain'		=> $domain ?? $this->domain,
			'secure'		=> $secure ?? $this->secure,
			'httponly'		=> $httpOnly ?? $this->httpOnly
		]);
	}
}

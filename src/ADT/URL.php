<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** * @noinspection PhpDocMissingThrowsInspection */

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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 */

namespace CeusMedia\Common\ADT;

use CeusMedia\Common\ADT\URL\Parts;
use InvalidArgumentException;
use RangeException;
use RuntimeException;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 *	@todo			code doc
 */
class URL
{
	/**	@var	URL|NULL			$defaultUrl */
	protected ?self $defaultUrl		= NULL;

	/**	@var	Parts				$parts */
	protected Parts $parts;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		string				$url		URL string to represent
	 *	@param		URL|string|NULL		$defaultUrl Underlying base URL
	 */
	public function __construct( string $url, URL|string|NULL $defaultUrl = NULL )
	{
		if( !is_null( $defaultUrl ) ){
			if( is_string( $defaultUrl ) )
				$defaultUrl	= new self( $defaultUrl );
			$this->setDefault( $defaultUrl );
		}
		if( 0 === strlen( trim( $url ) ) )
			throw new InvalidArgumentException( 'No URL given' );
		$this->set( $url );
	}

	public function __toString(): string
	{
		return $this->get();
	}

	/**
	 * @param		string|NULL			$url
	 * @param		URL|string|NULL		$defaultUrl
	 * @return		self
	 */
	public static function create( string $url = NULL, URL|string|NULL $defaultUrl = NULL ): self
	{
		return new self( $url, $defaultUrl );
	}

	public function get( bool $absolute = TRUE ): string
	{
		return $absolute ? $this->getAbsolute() : $this->getRelative();
	}

	/**
	 *	Returns set URL as absolute URL-
	 *	Alias for get() or get( TRUE ).
	 *	@access		public
	 *	@return		string		Absolute URL
	 */
	public function getAbsolute(): string
	{
		if( 0 === strlen( trim( $this->parts->scheme ) ) )
			throw new RuntimeException( 'HTTP scheme not set' );
		if( 0 === strlen( trim( $this->parts->host ) ) )
			throw new RuntimeException( 'HTTP host not set' );
		$buffer	= [];
		if( $this->parts->scheme )
			$buffer[]	= $this->parts->scheme.'://';
		if( $this->parts->user ){
			$buffer[]	= $this->parts->user;
			if( $this->parts->pass )
				$buffer[]	= ':'.$this->parts->pass;
			$buffer[]	= '@';
		}
		if( $this->parts->host )
			$buffer[]	= $this->parts->host;
		if( NULL !== $this->parts->port && 0 !== $this->parts->port )
			$buffer[]	= ':'.$this->parts->port;
		if( $this->parts->path )
			$buffer[]	= $this->parts->path;
		if( $this->parts->query )
			$buffer[]	= '?'.$this->parts->query;
		if( $this->parts->fragment )
			$buffer[]	= '#'.$this->parts->fragment;
		return join( '', $buffer );
	}

	/**
	 *	Returns set URL as relative URL.
	 *	Alias for get( FALSE ).
	 *	@access		public
	 *	@return		string		Relative URL
	 */
	public function getRelative(): string
	{
		$buffer	= [];
		if( $this->parts->path )
			$buffer[]	= ltrim( $this->parts->path, '/' );
		if( $this->parts->query )
			$buffer[]	= '?'.$this->parts->query;
		if( $this->parts->fragment )
			$buffer[]	= '#'.$this->parts->fragment;
		return join( '', $buffer );
	}

	/**
	 *	... (to be implemented)
	 *	@access		public
	 *	@todo		implement
	 *	@param		URL|string		$referenceUrl		Reference URL to apply to absolute URL
	 *	@return		string		... (to be implemented)
	 */
	public function getAbsoluteTo( URL|string $referenceUrl ): string
	{
		if( is_string( $referenceUrl ) )
			$referenceUrl	= new URL( $referenceUrl );
		if( !( $referenceUrl instanceof URL ) )
			throw new InvalidArgumentException( 'Given reference URL is neither URL object nor string' );
		$url	= clone $referenceUrl;
		$url->setPath( $this->parts->path );
		$url->setQuery( $this->parts->query );
		$url->setFragment( $this->parts->fragment );
		return $url->get();
	}

	/**
	 *	@access		public
	 *	@param		URL|string	$referenceUrl		Reference URL to apply to absolute URL
	 *	@return		string
	 */
	public function getRelativeTo( URL|string $referenceUrl ): string
	{
		if( is_string( $referenceUrl ) )
			$reference	= new self( $referenceUrl );
		else
			$reference	= $referenceUrl;

		if( $this->getScheme() !== $reference->getScheme() )
			throw new InvalidArgumentException( 'Schema not matching' );
		if( $this->getHost() !== $reference->getHost() )
			throw new InvalidArgumentException( 'Host not matching' );
		if( $this->getPort() !== $reference->getPort() )
			throw new InvalidArgumentException( 'Port not matching' );

		$query			= $this->getQuery() ? '?'.$this->getQuery() : '';
		$fragment		= $this->getFragment() ? '#'.$this->getFragment() : '';
		$referencePath	= $reference->getPath();
		if( str_starts_with( $this->getPath(), $referencePath ) )
			return substr( $this->getPath(), strlen( $referencePath ) ).$query.$fragment;

		$parts			= [];
		$pathParts		= explode( '/', ltrim( $this->getPath(), '/' ) );
		foreach( explode( '/', trim( $referencePath, '/' ) ) as $referencePathPart ){
			$part	= array_shift( $pathParts );
			if( $referencePathPart === $part )
				continue;
			array_unshift( $parts, '..' );
			$parts[]	= $part;
		}
		foreach( $pathParts as $part )
			$parts[]	= $part;
		return join( '/', $parts ).$query.$fragment;
	}

	public function getFragment(): string
	{
		return $this->parts->fragment;
	}

	public function getHost(): string
	{
		return $this->parts->host;
	}

	public function getPassword(): string
	{
		return $this->parts->pass;
	}

	public function getPath(): string
	{
		return $this->parts->path;
	}

	public function getPort(): ?int
	{
		return $this->parts->port;
	}

	public function getQuery(): string
	{
		return $this->parts->query;
	}

	public function getScheme(): string
	{
		return $this->parts->scheme;
	}

	public function getUsername(): string
	{
		return $this->parts->user;
	}

	public function isAbsolute(): bool
	{
		$hasScheme		= strlen( $this->parts->scheme ) > 0;
		$hasHost		= strlen( $this->parts->host ) > 0;
		$hasPath		= strlen( $this->parts->path ) > 0;
		return $hasScheme && $hasHost && $hasPath;
	}

	public function isRelative(): bool
	{
		return !$this->isAbsolute() && 0 !== strlen( trim( $this->parts->path ) );
	}

	public function set( string $url ): self
	{
		if( 0 === strlen( trim( $url ) ) )
			throw new InvalidArgumentException( 'Empty URL given' );
		$parts	= parse_url( trim( $url ) );
		if( $parts === FALSE )
			throw new InvalidArgumentException( 'No valid URL given' );
		$defaults	= [
			'scheme'		=> $this->defaultUrl ? $this->defaultUrl->getScheme() : '',
			'host'			=> $this->defaultUrl ? $this->defaultUrl->getHost() : '',
			'port'			=> $this->defaultUrl?->getPort(),
			'user'			=> $this->defaultUrl ? $this->defaultUrl->getUsername() : '',
			'pass'			=> $this->defaultUrl ? $this->defaultUrl->getPassword() : '',
			'query'			=> '',
			'fragment'		=> '',
		];
		if( $this->defaultUrl && $this->defaultUrl->parts->path !== '/' ){
			$regExp			= '@^'.preg_quote( $this->defaultUrl->parts->path ).'@';
			$parts['path']	= preg_replace( $regExp, '/', $parts['path'] ?? '' );
		}
		$this->parts	= Parts::fromArray( array_merge( $defaults, $parts ) );
		$this->setPath( '/'.ltrim( $parts['path'] ?? '', '/' ) );
		return $this;
	}

	public function setAuth( string $username, string $password ): self
	{
		$this->setUsername( $username );
		$this->setPassword( $password );
		return $this;
	}

	public function setDefault( URL $url ): self
	{
		$this->defaultUrl	= $url;
		return $this;
	}

	public function setFragment( string $fragment ): self
	{
		$this->parts->fragment	= $fragment;
		return $this;
	}

	public function setHost( string $host, ?int $port = NULL, string $username = NULL, string $password = NULL ): self
	{
		$this->parts->host	= $host;
		if( NULL !== $port )
			$this->setPort( $port );
		if( NULL !== $username )
			$this->setAuth( $username, $password );
		return $this;
	}

	public function setPassword( string $password ): self
	{
		$this->parts->pass	= $password;
		return $this;
	}

	public function setPort( ?int $port = NULL ): self
	{
		$this->parts->port	= $port;
		return $this;
	}

	public function setPath( string $path, bool $based = FALSE ): self
	{
		$path	= preg_replace( '@([^/]+/\.\./)@', '/', $path );
		if( preg_match( '@\.\./@', $path ) )
			throw new RangeException( 'Invalid `change dir up` (../)' );
		$path	= '/'.ltrim( $path, '/' );
		if( $based && $this->defaultUrl )
			$path	= rtrim( $this->defaultUrl->getPath(), '/' ).$path;
		$this->parts->path	= $path;
		return $this;
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		array|string		$query
	 *	@return		self
	 */
	public function setQuery( array|string $query ): self
	{
		if( is_array( $query ) )
			$query	= http_build_query( $query, '&' );
		$this->parts->query	= $query;
		return $this;
	}

	public function setScheme( string $scheme ): self
	{
		$this->parts->scheme	= $scheme;
		return $this;
	}

	public function setUsername( string $username ): self
	{
		$this->parts->user	= $username;
		return $this;
	}
}

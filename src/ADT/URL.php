<?php /** @noinspection PhpMultipleClassDeclarationsInspection */
/** * @noinspection PhpDocMissingThrowsInspection */

/**
 *	...
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 */

namespace CeusMedia\Common\ADT;

use CeusMedia\Common\ADT\URL\Parts;
use CeusMedia\Common\Renderable;
use InvalidArgumentException;
use RangeException;
use RuntimeException;
use Stringable;

/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://www.w3.org/Addressing/URL/url-spec.html
 *	@todo			code doc
 */
class URL implements Renderable, Stringable
{
	/**	@var	URL|NULL			$defaultUrl */
	protected ?URL $defaultUrl		= NULL;

	/**	@var	Parts				$parts */
	protected Parts $parts;

	/**
	 *	Constructor.
	 *
	 *	@access		public
	 *	@param		string				$url		URL string to represent
	 *	@param		URL|string|NULL		$defaultUrl Underlying base URL
	 */
	public function __construct( string $url, URL|string $defaultUrl = NULL )
	{
		if( '' === trim( $url ) )
			throw new InvalidArgumentException( 'No URL given' );

		if( NULL !== $defaultUrl ){
			if( is_string( $defaultUrl ) )
				$defaultUrl	= new URL( $defaultUrl );
			$this->setDefault( $defaultUrl );
		}
		$this->set( $url );
	}

	/**
	 *	Implements Stringable interface.
	 *	@return		string
	 */
	public function __toString(): string
	{
		return $this->get();
	}

	/**
	 * @param		string|NULL			$url
	 * @param		URL|string|NULL		$defaultUrl
	 * @return		static
	 */
	public static function create( string $url = NULL, URL|string $defaultUrl = NULL ): static
	{
		$className	= static::class;
		return new $className( $url, $defaultUrl );
	}

	/**
	 *	Returns set URL as absolute or relative URL.
	 *	Alias for getAbsolute() or getRelative().
	 *	@access		public
	 *	@return		string		Absolute URL
	 */
	public function get( bool $absolute = TRUE ): string
	{
		return $absolute ? $this->getAbsolute() : $this->getRelative();
	}

	/**
	 *	Returns set URL as absolute URL.
	 *	Alias for get() or get( TRUE ).
	 *	@access		public
	 *	@return		string		Absolute URL
	 */
	public function getAbsolute(): string
	{
		if( '' === trim( $this->parts->scheme ?? '' ) )
			throw new RuntimeException( 'HTTP scheme not set' );
		if( '' === trim( $this->parts->host ?? '' ) )
			throw new RuntimeException( 'HTTP host not set' );
		$buffer	= [$this->parts->scheme.'://'];
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

	/**
	 *	Returns parsed or set fragment component.
	 *	@return		string
	 */
	public function getFragment(): string
	{
		return $this->parts->fragment;
	}

	/**
	 *	Returns parsed or set host component.
	 *	@return		string
	 */
	public function getHost(): string
	{
		return $this->parts->host;
	}

	/**
	 *	Returns parsed or set user password for authentication.
	 *	@return		string
	 */
	public function getPassword(): string
	{
		return $this->parts->pass;
	}

	/**
	 *	Returns parsed or set path component.
	 *	@return		string
	 */
	public function getPath(): string
	{
		return $this->parts->path;
	}

	/**
	 *	Returns parsed or set port component.
	 *	@return		?int
	 */
	public function getPort(): ?int
	{
		return $this->parts->port;
	}

	/**
	 *	Returns parsed or set query component.
	 *	@return		string
	 */
	public function getQuery(): string
	{
		return $this->parts->query;
	}

	/**
	 *	Returns parsed or set scheme component.
	 *	@return		string
	 */
	public function getScheme(): string
	{
		return $this->parts->scheme;
	}

	/**
	 *	Returns parsed or set username for authentication.
	 *	@return		string
	 */
	public function getUsername(): string
	{
		return $this->parts->user;
	}

	/**
	 *	Indicates whether parsed or set URLis absolute.
	 *	Means: available components are enough for an absolute path.
	 *	Means: has at least non-empty scheme, host and path components
	 *	@return		bool
	 */
	public function isAbsolute(): bool
	{
		$hasScheme		= strlen( $this->parts->scheme ) > 0;
		$hasHost		= strlen( $this->parts->host ) > 0;
		$hasPath		= strlen( $this->parts->path ) > 0;
		return $hasScheme && $hasHost && $hasPath;
	}

	/**
	 *	Indicates whether parsed or set URL is relative.
	 *	Means: available components are NOT enough for an absolute path, but at least a path is set.
	 *	Means: has no scheme or host components
	 *	@return		bool
	 */
	public function isRelative(): bool
	{
		return !$this->isAbsolute() && '' !== trim( $this->parts->path );
	}

	/**
	 *	Implements Renderable interface.
	 *	@return		string
	 */
	public function render(): string
	{
		return $this->get();
	}

	/**
	 *	Opens a URL by parsing its components.
	 *	Given argument can be a string or a URL object.
	 *	Accepts objects of classes implementing Stringable interface.
	 *	Supports Renderable interface as well.
	 *	@param		URL|Renderable|Stringable|string		$url
	 *	@return		static
	 *	@throws		InvalidArgumentException	if given URL is empty
	 *	@throws		InvalidArgumentException	of given URL is invalid
	 */
	public function set( URL|Renderable|Stringable|string $url ): static
	{
		$url	= $url instanceof Renderable ? $url->render() : (string) $url;
		if( '' === trim( $url ) )
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
		if( NULL !== $this->defaultUrl && '/' !== $this->defaultUrl->getPath() ){
			$regExp			= '@^'.preg_quote( $this->defaultUrl->getPath() ).'@';
			$parts['path']	= preg_replace( $regExp, '/', $parts['path'] ?? '' );
		}
		$this->parts	= Parts::fromArray( array_merge( $defaults, $parts ) );
		$this->setPath( '/'.ltrim( $parts['path'] ?? '', '/' ) );
		return $this;
	}

	/**
	 *	Set credentials (username + password) to enable authentication.
	 *	@param		string		$username
	 *	@param		string		$password
	 *	@return		static
	 */
	public function setAuth( string $username, string $password ): static
	{
		$this->setUsername( $username );
		$this->setPassword( $password );
		return $this;
	}

	public function setDefault( URL $url ): static
	{
		$this->defaultUrl	= $url;
		return $this;
	}

	/**
	 *	Set path fragment.
	 *	@param		string		$fragment
	 *	@return		static
	 */
	public function setFragment( string $fragment ): static
	{
		$this->parts->fragment	= $fragment;
		return $this;
	}

	public function setHost( string $host, ?int $port = NULL, string $username = NULL, string $password = NULL ): static
	{
		$this->parts->host	= $host;
		if( NULL !== $port )
			$this->setPort( $port );
		if( NULL !== $username )
			$this->setAuth( $username, $password );
		return $this;
	}

	/**
	 *	Set password for username.
	 *	@param		string		$password
	 *	@return		static
	 */
	public function setPassword( string $password ): static
	{
		$this->parts->pass	= $password;
		return $this;
	}

	/**
	 *	Set or unset port component of URL (between domain and path or query or fragment).
	 *	@param		?int		$port
	 *	@return		static
	 */
	public function setPort( ?int $port = NULL ): static
	{
		$this->parts->port	= $port;
		return $this;
	}

	/**
	 *	Set path component of URL (between domain and query or fragment).
	 *	@param		string		$path
	 *	@param		bool		$based		Flag: use base URL if set, default: no
	 *	@return		static
	 */
	public function setPath( string $path, bool $based = FALSE ): static
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
	 *	Set query component (between path and fragment).
	 *	...
	 *	@access		public
	 *	@param		array|string		$query
	 *	@return		static
	 */
	public function setQuery( array|string $query ): static
	{
		if( is_array( $query ) )
			$query	= http_build_query( $query, '&' );
		$this->parts->query	= $query;
		return $this;
	}

	/**
	 *	Set URL scheme (before domain).
	 *	@param		string		$scheme
	 *	@return		static
	 */
	public function setScheme( string $scheme ): static
	{
		$this->parts->scheme	= $scheme;
		return $this;
	}

	/**
	 *	Set username for authentication.
	 *	@param		string		$username
	 *	@return		static
	 */
	public function setUsername( string $username ): static
	{
		$this->parts->user	= $username;
		return $this;
	}
}

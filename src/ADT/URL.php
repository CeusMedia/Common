<?php
/**
 *	...
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
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 */
/**
 *	...
 *	@category		Library
 *	@package		CeusMedia_Common_ADT
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2020 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			http://www.w3.org/Addressing/URL/url-spec.html
 *	@todo			code doc
 */
class ADT_URL
{
	protected $defaultUrl;
	protected $parts;

	public function __construct( $url, $defaultUrl = NULL )
	{
		if( !is_null( $defaultUrl ) )
			$this->setDefault( $defaultUrl );
		if( !( is_string( $url ) && strlen( trim( $url ) ) ) )
			throw new InvalidArgumentException( 'No URL given' );
		return $this->set( $url );
	}

	public function __toString()
	{
		return $this->get();
	}

	public static function create( $url = NULL, $defaultUrl = NULL )
	{
		return new static( $url, $defaultUrl );
	}

	public function get( $absolute = TRUE )
	{
		return $absolute ? $this->getAbsolute() : $this->getRelative();
	}

	/**
	 *	Returns set URL as absolute URL-
	 *	Alias for get() or get( TRUE ).
	 *	@access		public
	 *	@return		string		Absolute URL
	 */
	public function getAbsolute(){
		if( empty( $this->parts->scheme ) )
			throw new RuntimeException( 'HTTP scheme not set' );
		if( empty( $this->parts->host ) )
			throw new RuntimeException( 'HTTP host not set' );
		$buffer	= array();
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
		if( $this->parts->port )
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
	 *	Returns set URL as relative URL-
	 *	Alias for get( FALSE ).
	 *	@access		public
	 *	@return		string		Relative URL
	 */
	public function getRelative(){
		$buffer	= array();
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
	 *	@param		ADT_URL|string	$referenceUrl		Reference URL to apply to absolute URL
	 *	@return		string		... (to be implemented)
	 */
	public function getAbsoluteTo( $referenceUrl ){
		if( is_string( $referenceUrl ) )
			$referenceUrl	= new ADT_URL( $referenceUrl );
		if( !( is_a( $referenceUrl, 'ADT_URL' ) ) )
			throw new InvalidArgumentException( 'Given reference URL is neither ADT_URL nor string' );
		$url	= clone $referenceUrl;
		$url->setPath( $this->parts->path );
		$url->setQuery( $this->parts->query );
		$url->setFragment( $this->parts->fragment );
		return $url->get();
	}

	/**
	 *	... (to be implemented)
	 *	@access		public
	 *	@todo		implement
	 *	@param		ADT_URL|string	$referenceUrl		Reference URL to apply to absolute URL
	 *	@return		string		... (to be implemented)
	 */
	public function getRelativeTo( $referenceUrl ){
		throw new Exception( 'No implemented, yet' );
		return '';
	}

	public function getFragment(){
		return $this->parts->fragment;
	}

	public function getHost(){
		return $this->parts->host;
	}

	public function getPassword(){
		return $this->parts->pass;
	}

	public function getPath(){
		return $this->parts->path;
	}

	public function getPort(){
		return $this->parts->port;
	}

	public function getQuery(){
		return $this->parts->query;
	}

	public function getScheme(){
		return $this->parts->scheme;
	}

	public function getUsername(){
		return $this->parts->user;
	}

	public function isAbsolute()
	{
		$hasScheme		= strlen( $this->parts->scheme ) > 0;
		$hasHost		= strlen( $this->parts->host ) > 0;
		$hasPath		= strlen( $this->parts->path ) > 0;
		return $hasScheme && $hasHost && $hasPath;
	}

	public function isRelative()
	{
		return !$this->isAbsolute() && strlen( trim( $this->parts->path ) );
	}

	public function set( $url, $strict = TRUE )
	{
		if( !( is_string( $url ) && strlen( trim( $url ) ) ) ){
			if( $strict )
				throw new InvalidArgumentException( 'Empty URL given' );
			return $this;
		}
		$parts	= parse_url( trim( $url ) );
		if( $parts === FALSE )
			throw new InvalidArgumentException( 'No valid URL given' );
		$defaults	= array(
			'scheme'		=> $this->defaultUrl ? $this->defaultUrl->getScheme() : '',
			'host'			=> $this->defaultUrl ? $this->defaultUrl->getHost() : '',
			'port'			=> $this->defaultUrl ? $this->defaultUrl->getPort() : '',
			'user'			=> $this->defaultUrl ? $this->defaultUrl->getUsername() : '',
			'pass'			=> $this->defaultUrl ? $this->defaultUrl->getPassword() : '',
			'query'			=> '',
			'fragment'		=> '',
		);
		if( $this->defaultUrl && $this->defaultUrl->parts->path !== '/' ){
			$regExp			= '@^'.preg_quote( $this->defaultUrl->parts->path ).'@';
			$parts['path']	= preg_replace( $regExp, '/', $parts['path'] );
		}
		$this->parts	= (object) array_merge( $defaults, $parts );
		$this->setPath( '/'.ltrim( $parts['path'] , '/' ) );
		return $this;
	}

	public function setAuth( $username, $password )
	{
		$this->setUsername( $username );
		$this->setPassword( $password );
		return $this;
	}

	public function setDefault( $url )
	{
		$this->defaultUrl	= NULL;
		if( is_null( $url ) || !strlen( trim( $url ) ) )
			return $this;
		if( is_string( $url ) && strlen( trim( $url ) ) )
			$url	= new ADT_URL( $url );
		if( $url && !( $url instanceof ADT_URL ) )
			throw new InvalidArgumentException( 'Default URL must be ADT_URL or string' );
		$this->defaultUrl	= $url;
		return $this;
	}

	public function setFragment( $fragment )
	{
		$this->parts->fragment	= $fragment;
		return $this;
	}

	public function setHost( $host, $port = NULL, $username = NULL, $password = NULL )
	{
		$this->parts->host	= $host;
		if( $port )
			$this->setPort( $port );
		if( $username )
			$this->setAuth( $username, $password );
		return $this;
	}

	public function setPassword( $password )
	{
		$this->parts->pass	= $password;
		return $this;
	}

	public function setPath( $path, $based = FALSE )
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

	public function setQuery( $query )
	{
		if( is_array( $query ) )
			$query	= http_build_query( $query, '&' );
		$this->parts->query	= $query;
		return $this;
	}

	public function setScheme( $scheme )
	{
		$this->parts->scheme	= $scheme;
		return $this;
	}

	public function setUsername( $username )
	{
		$this->parts->user	= $username;
		return $this;
	}
}

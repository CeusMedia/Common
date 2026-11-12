<?php /** @noinspection PhpComposerExtensionStubsInspection */

declare(strict_types=1);

/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Contents from the Net.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net;

use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\HTTP\Response\Parser as HttpResponseParser;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Reader for Contents from the Net.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>IoException
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	/**	@var		string|NULL			$body			Result content of response */
	protected ?string $body				= NULL;

	/**	@var		array				$headers		Map of response headers */
	protected array $headers			= [];

	/**	@var		array				$info			Map of information of last request */
	protected array $info				= [];

	/**	@var		string|NULL			$url			URL to read */
	protected ?string $url				= NULL;

	/**	@var		string				$agent			User Agent */
	protected static string $userAgent	= 'CeusMedia-Common/1.1 Net\\Reader';

	/**	@var		string				$username		Username for Basic Authentication */
	protected string $username			= "";

	/**	@var		string				$password		Password for Basic Authentication */
	protected string $password			= "";

	/**	@var		boolean				$verifyHost		Flag: verify Host, enabled by default */
	protected bool $verifyHost 			= TRUE;

	/**	@var		boolean				$verifyPeer		Flag: verify Peer, enabled by default */
	protected bool $verifyPeer			= TRUE;

	/**	@var		integer				$timeout		Maximum time of request in seconds, 0 for default of cURL (no limit until 1.2) */
	protected int $timeout				= 0;

	/**	@var		integer				$connectTimeout	Maximum time to connect in seconds, 0 for default of cURL (until 1.2) */
	protected int $connectTimeout		= 0;

	/**	@var		string|NULL			$proxyAddress	Domain or IP (and port) of proxy server */
	protected ?string $proxyAddress		= NULL;

	/**	@var		string|NULL			$proxyAuth		Username and password for proxy server authentication */
	protected ?string $proxyAuth		= NULL;

	/**	@var		integer				$proxyType		Type of proxy server (CURLPROXY_HTTP | CURLPROXY_SOCKS5) */
	protected int $proxyType			= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$url			URL to read
	 *	@return		void
	 */
	public function __construct( ?string $url = NULL )
	{
		if( $url !== NULL )
			$this->setUrl( $url );
	}

	/**
	 *	Creates cURL instance for set URL, configured by reader settings and given cURL options.
	 *	TLS certificate and host are verified by default, see setVerifyPeer() and setVerifyHost().
	 *	@access		protected
	 *	@param		array		$curlOptions		Map of cURL options, overriding reader settings
	 *	@return		CURL
	 *	@throws		InvalidArgumentException	if an option key is invalid
	 */
	protected function createCurl( array $curlOptions = [] ): CURL
	{
		$curl		= new CURL( $this->url );

		$curl->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost ? 2 : 0 );
		$curl->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
		//  timeouts are only set if configured, so a global timeout set by Net\CURL::setTimeOut() still applies
		if( $this->timeout > 0 )
			$curl->setOption( CURLOPT_TIMEOUT, $this->timeout );
		if( $this->connectTimeout > 0 )
			$curl->setOption( CURLOPT_CONNECTTIMEOUT, $this->connectTimeout );
		$curl->setOption( CURLOPT_FOLLOWLOCATION, !ini_get( 'safe_mode' ) && !ini_get( 'open_basedir' ) );
		$curl->setOption( CURLOPT_HEADER, TRUE );
		if( self::$userAgent )
			$curl->setOption( CURLOPT_USERAGENT, self::$userAgent );
		if( $this->username )
			$curl->setOption( CURLOPT_USERPWD, $this->username.":".$this->password );
		if( $this->proxyAddress ){
			$curl->setOption( CURLOPT_HTTPPROXYTUNNEL, TRUE);
			$curl->setOption( CURLOPT_PROXY, $this->proxyAddress );
			$curl->setOption( CURLOPT_PROXYTYPE, $this->proxyType );
			if( $this->proxyAuth )
				$curl->setOption( CURLOPT_PROXYUSERPWD, $this->proxyAuth );
		}

		foreach( $curlOptions as $key => $value ){
			if( is_string( $key ) ){
				if( !( str_starts_with( $key, "CURLOPT_" ) && defined( $key ) ) )
					throw new InvalidArgumentException( 'Invalid option constant key "'.$key.'"' );
				$key	= constant( $key );
			}
			if( !is_int( $key ) )
				throw new InvalidArgumentException( 'Option must be given as integer or string' );
			$curl->setOption( $key, $value );
		}
		return $curl;
	}

	public function getBody(): string
	{
		if( !$this->info )
			throw new RuntimeException( "No Request has been sent, yet." );
		return $this->body;
	}

	/**
	 *	Returns Headers Array or a specified header from last request.
	 *	@access		public
	 *	@param		string|NULL		$key		Header key
	 *	@return		mixed
	 */
	public function getHeader( ?string $key = NULL ): mixed
	{
		if( !$this->info )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->headers;
		if( !array_key_exists( $key, $this->headers ) )
			throw new InvalidArgumentException( 'Header Key "'.$key.'" is invalid.' );
		return $this->headers[$key];
	}

	public function getHeaders(): array
	{
		return $this->headers;
	}

	/**
	 *	Returns information map or single information from last request.
	 *	@access		public
	 *	@param		string|NULL		$key		Information key
	 *	@return		mixed
	 */
	public function getInfo( ?string $key = NULL ): mixed
	{
		if( !$this->info )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->info;
		if( !array_key_exists( $key, $this->info ) )
			throw new InvalidArgumentException( 'Status Key "'.$key.'" is invalid.' );
		return $this->info[$key];
	}

	/**
	 *	Returns URL to read.
	 *	@access		public
	 *	@return		string
 		 */
	public function getUrl(): string
	{
		return $this->url;
	}

	/**
	 *	Returns set user agent.
	 *	@access		public
	 *	@return		string
	 */
	public function getUserAgent(): string
	{
		return self::$userAgent;
	}

	/**
	 *	Requests set URL and returns response.
	 *	@access		public
	 *	@param		array		$curlOptions		Map of cURL options
	 *	@return		string
	 *	@throws		InvalidArgumentException
	 *	@throws		IoException
	 *	@todo		Auth
	 */
	public function read( array $curlOptions = [] ): string
	{
		$curl			= $this->createCurl( $curlOptions );
		$result			= $curl->exec( TRUE, FALSE );
		$response		= HttpResponseParser::fromString( $result );

		$this->body		= $response->getBody();
		$this->headers	= $response->getHeaders();
		$this->info		= $curl->getInfo();
		$code			= $curl->getInfo( CURL::INFO_HTTP_CODE );
		$error			= $curl->getInfo( CURL::INFO_ERROR );
		$errno			= $curl->getInfo( CURL::INFO_ERRNO );
		if( $errno )
			throw IoException::create( 'HTTP request failed: '.$error, $errno )->setResource( $this->url );
		if( !in_array( $code, ['200', '301', '303', '304', '307'] ) )
			throw IoException::create( 'HTTP request failed', $code )->setResource( $this->url );
		return $this->body;
	}

	/**
	 *	Requests URL and returns Response statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$url			URL to request
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		string
	 *	@throws		InvalidArgumentException	if no URL is set
	 *	@throws		IoException
	 *	@todo		Auth
	 */
	public static function readUrl( string $url, array $curlOptions = [] ): string
	{
		$reader	= new Reader( $url );
		return $reader->read( $curlOptions );
	}

	/**
	 *	Set Username and Password for Basic Auth.
	 *	@access		public
	 *	@param		string		$username	Basic Auth Username
	 *	@param		string		$password	Basic Auth Password
	 *	@return		self
	 */
	public function setBasicAuth( string $username, string $password ): self
	{
		$this->username	= $username;
		$this->password	= $password;
		return $this;
	}

	/**
	 *	Sets maximum time to connect in seconds (CURLOPT_CONNECTTIMEOUT).
	 *	Default: 0, which keeps the default of cURL (300 seconds) or Net\CURL::setTimeOut().
	 *	A default of 10 seconds will follow in 1.2.
	 *	@access		public
	 *	@param		integer		$seconds	Maximum time to connect in seconds
	 *	@return		self
	 */
	public function setConnectTimeout( int $seconds ): self
	{
		$this->connectTimeout	= max( 0, $seconds );
		return $this;
	}

	/**
	 *	Sets proxy domain or IP.
	 *	@access		public
	 *	@param		string			$address	Domain or IP (and Port) of proxy server
	 *	@param		integer			$type		Type of proxy server (CURLPROXY_HTTP | CURLPROXY_SOCKS5 )
	 *	@param		string|NULL		$auth		Username and password for proxy authentication
	 *	@return		self
	 */
	public function setProxy( string $address, int $type = CURLPROXY_HTTP, ?string $auth = NULL ): self
	{
		$this->proxyAddress	= $address;
		$this->proxyType	= $type;
		$this->proxyAuth	= $auth;
		return $this;
	}

	/**
	 *	Sets maximum time of request in seconds (CURLOPT_TIMEOUT).
	 *	Default: 0, which keeps the default of cURL (no limit) or Net\CURL::setTimeOut().
	 *	A default of 30 seconds will follow in 1.2.
	 *	@access		public
	 *	@param		integer		$seconds	Maximum time of request in seconds
	 *	@return		self
	 */
	public function setTimeout( int $seconds ): self
	{
		$this->timeout	= max( 0, $seconds );
		return $this;
	}

	/**
	 *	Set URL to request.
	 *	@access		public
	 *	@param		string		$url		URL to request
	 *	@return		self
	 */
	public function setUrl( string $url ): self
	{
		if( strlen( trim( $url ) ) === 0 )
			throw new InvalidArgumentException( "No URL given." );
		$this->url	= $url;
		return $this;
	}

	/**
	 *	Sets User Agent.
	 *	@access		public
	 *	@param		string		$title		User Agent to set
	 *	@return		self
	 */
	public function setUserAgent( string $title ): self
	{
		self::$userAgent	= $title;
		return $this;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYHOST (TRUE: 2, FALSE: 0). Enabled by default.
	 *	@access		public
	 *	@param		bool		$verify		Flag: verify Host
	 *	@return		self
	 */
	public function setVerifyHost( bool $verify ): self
	{
		$this->verifyHost	= $verify;
		return $this;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYPEER. Enabled by default.
	 *	@access		public
	 *	@param		bool		$verify		Flag: verify Peer
	 *	@return		self
	 */
	public function setVerifyPeer( bool $verify ): self
	{
		$this->verifyPeer	= $verify;
		return $this;
	}
}

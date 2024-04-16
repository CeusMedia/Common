<?php /** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for Contents from the Net.
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
 *	@package		CeusMedia_Common_Net
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
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
 *	@copyright		2007-2024 Christian Würker
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
	protected static string $userAgent	= "cmClasses:Net_Reader/0.7";

	/**	@var		string				$username		Username for Basic Authentication */
	protected string $username			= "";

	/**	@var		string				$password		Password for Basic Authentication */
	protected string $password			= "";

	/**	@var		boolean				$verifyHost		Flag: verify Host */
	protected bool $verifyHost 			= FALSE;

	/**	@var		boolean				$verifyPeer		Flag: verify Peer */
	protected bool $verifyPeer			= FALSE;

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
	public function getHeader( ?string $key = NULL )
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
	public function getInfo( ?string $key = NULL )
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
		$curl		= new CURL( $this->url );

		$curl->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		$curl->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
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
	 *	Sets Option CURLOPT_SSL_VERIFYHOST.
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
	 *	Sets Option CURLOPT_SSL_VERIFYPEER.
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

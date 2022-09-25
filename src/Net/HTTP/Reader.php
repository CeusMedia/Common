<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Reader for HTTP Resources.
 *
 *	Copyright (c) 2010-2022 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\Net\CURL;
use CeusMedia\Common\Net\HTTP\Header\Renderer;
use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;
use CeusMedia\Common\Net\HTTP\Response\Decompressor as ResponseDecompressor;
use CeusMedia\Common\Net\HTTP\Response\Parser as ResponseParser;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Handler for HTTP Requests.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2010-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	protected $curl;

	protected $curlInfo			= [];

	protected $responseHeaders	= [];

	//  default user agent to report to server, can be overridden by constructor or given CURL options on get or post
	protected $userAgent		= "CeusMediaCommon:Net.HTTP.Reader/0.9";

	/**
	 *	Constructor, sets up cURL.
	 *	@access		public
	 *	@param		string|NULL		$httpVersion		HTTP Version, 1.0 by default
	 *	@param		string|NULL		$userAgent			User Agent to report to server
	 */
	public function __construct( ?string $httpVersion = NULL, ?string $userAgent = NULL )
	{
		$this->curl		= new CURL;
		$this->curl->setOption( CURLOPT_ENCODING, '' );
		$this->curl->setOption( CURLOPT_HTTP_VERSION, $httpVersion );
		if( $userAgent )
			$this->userAgent	= $userAgent;
		$this->curl->setOption( CURLOPT_USERAGENT, $this->userAgent );
	}

	/**
	 *	Applies cURL Options to a cURL Object.
	 *	@access		protected
	 *	@param		CURL		$curl				cURL Object
	 *	@param		array		$options			Map of cURL Options
	 *	@return		void
	 */
	protected function applyCurlOptions( CURL $curl, array $options = [] )
	{
		foreach( $options as $key => $value ){
			if( is_string( $key ) ){
				if( !( preg_match( "@^CURLOPT_@", $key ) && defined( $key ) ) )
					throw new InvalidArgumentException( 'Invalid option constant key "'.$key.'"' );
				$key	= constant( $key );
			}
			if( !is_int( $key ) )
				throw new InvalidArgumentException( 'Option must be given as integer or string' );
			$curl->setOption( $key, $value );
		}
	}

	/**
	 *	Returns Resource Response.
	 *	@access		public
	 *	@param		string					$url			Resource URL
	 *	@param		array|HeaderSection		$headers		Map of HTTP Header Fields or Header Section Object
	 *	@param		array					$curlOptions	Map of cURL Options
	 *	@return		Response
	 */
	public function get( string $url, $headers = [], array $curlOptions = [] ): Response
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers ){
			if( $headers instanceof HeaderSection )
				$headers	= Renderer::render( $headers );
			$curlOptions[CURLOPT_HTTPHEADER]	= $headers;
		}
		$this->applyCurlOptions( $curl, $curlOptions );
		$response		= $curl->exec( TRUE, TRUE );
		$this->curlInfo	= $curl->getInfo();
		$this->headers	= $curl->getHeaders();
		$response		= ResponseParser::fromString( $response );
/*		$encodings	= $response->headers->getField( 'content-encoding' );
		while( $encoding = array_pop( $encodings ) )
		{
			$decompressor	= new ResponseDecompressor;
			$type			= $encoding->getValue();
			$body			= $decompressor->decompressString( $response->getBody(), $type );
		}
		$response->setBody( $body );*/
		return $response;
	}

	/**
	 *	Returns Info Array or single Information from last cURL Request.
	 *	@access		public
	 *	@param		string|NULL		$key		Information Key
	 *	@return		mixed
	 */
	public function getCurlInfo( ?string $key = NULL )
	{
		if( !$this->curlInfo )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->curlInfo;
		if( !array_key_exists( $key, $this->curlInfo ) )
			throw new InvalidArgumentException( 'Status Key "'.$key.'" is invalid.' );
		return $this->curlInfo[$key];
	}

	/**
	 *	...
	 *	@access		public
	 *	@param		string		$key
	 *	@return		mixed|NULL
	 */
	public function getResponseHeader( string $key )
	{
		return $this->responseHeaders[$key] ?? NULL;
	}

	/**
	 *	Posts Data to Resource and returns Response.
	 *	@access		public
	 *	@param		string					$url			Resource URL
	 *	@param		array					$data			Map of POST Data
	 *	@param		array|HeaderSection		$headers		Map of HTTP Header Fields or Header Section Object
	 *	@param		array					$curlOptions	Map of cURL Options
	 *	@return		Response
	 */
	public function post( string $url, array $data, $headers = [], array $curlOptions = [] ): Response
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers ){
			if( $headers instanceof HeaderSection )
				$headers	= Renderer::render( $headers );
			$curlOptions[CURLOPT_HTTPHEADER]	= $headers;
		}
		$this->applyCurlOptions( $curl, $curlOptions );

		//  cURL hack (file upload identifier)
		foreach( $data as $key => $value )
			//  leading @ in field values
			if( is_string( $value ) && substr( $value, 0, 1 ) == "@" )
				//  need to be escaped
				$data[$key]	= "\\".$value;

		$curl->setOption( CURLOPT_POST, TRUE );
		$curl->setOption( CURLOPT_POSTFIELDS, http_build_query( $data, NULL, "&" ) );

		$response		= $curl->exec( TRUE, FALSE );
		$this->curlInfo	= $curl->getInfo();
		return ResponseParser::fromString( $response );
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
		$this->curl->setOption( CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		$this->curl->setOption( CURLOPT_USERPWD, $username.":".$password );
		return $this;
	}

	/**
	 *	Sets a cURL Option for all Requests.
	 *	@access		public
	 *	@param		integer		$key		Constant Value of cURL Option
	 *	@param		mixed		$value		Option Value
	 *	@return		void
	 *	@link		http://www.php.net/manual/en/function.curl-setopt.php
	 */
	public function setCurlOption( int $key, $value ): self
	{
		$this->curl->setOption( $key, $value );
		return $this;
	}

	/**
	 *	Sets Type of HTTP Compression (Encoding).
	 *	@access		public
	 *	@param		string		$method		Compression Type (gzip|deflate)
	 *	@return		self
	 */
	public function setEncoding( string $method ): self
	{
		$this->curl->setOption( CURLOPT_ENCODING, $method );
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
		$this->curl->setOption( CURLOPT_HTTPPROXYTUNNEL, TRUE );
		$this->curl->setOption( CURLOPT_PROXY, $address );
		$this->curl->setOption( CURLOPT_PROXYTYPE, $type );
		if( $auth )
			$this->curl->setOption( CURLOPT_PROXYUSERPWD, $auth );
		return $this;
	}

	/**
	 *	Sets User Agent.
	 *	@access		public
	 *	@param		string		$string		User Agent to set
	 *	@return		self
	 */
	public function setUserAgent( string $string ): self
	{
		if( empty( $string ) )
			throw new InvalidArgumentException( 'Must be set' );
		$this->curl->setOption( CURLOPT_USERAGENT, $string );
		return $this;
	}

	/**
	 *	Sets up SSL Verification.
	 *	@access		public
	 *	@param		boolean			$host		Flag: verify Host
	 *	@param		integer			$peer		Flag: verify Peer
	 *	@param		string|NULL		$caPath		Path to certificates
	 *	@param		string|NULL		$caInfo		Certificate File Name
	 *	@return		void
	 */
	public function setVerify( bool $host = FALSE, int $peer = 0, ?string $caPath = NULL, ?string $caInfo = NULL ): self
	{
		$this->curl->setOption( CURLOPT_SSL_VERIFYHOST, $host );
		$this->curl->setOption( CURLOPT_SSL_VERIFYPEER, $peer );
		$this->curl->setOption( CURLOPT_CAPATH, $caPath );
		$this->curl->setOption( CURLOPT_CAINFO, $caInfo );
		return $this;
	}
}

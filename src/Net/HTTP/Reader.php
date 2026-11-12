<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Reader for HTTP Resources.
 *
 *	Copyright (c) 2010-2026 Christian Würker (ceusmedia.de)
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
 *	@copyright		2010-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\Net\CURL;
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
 *	@copyright		2010-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Reader
{
	protected CURL $curl;

	protected array $curlInfo			= [];

	protected array $responseHeaders	= [];

	//  default user agent to report to server, can be overridden by constructor or given CURL options on get or post
	protected string $userAgent			= "CeusMediaCommon:Net.HTTP.Reader/1.0";

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
	protected function applyCurlOptions( CURL $curl, array $options = [] ): void
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
	public function get( string $url, array|HeaderSection $headers = [], array $curlOptions = [] ): Response
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers ){
			if( $headers instanceof HeaderSection )
				$header	= $headers;
			else
				$header	= HeaderSection::getInstance()->addFieldPairs( $headers );
			$curlOptions[CURLOPT_HTTPHEADER]	= array_map( static fn( $field ) => $field->toString(), $header->getFields() );
		}
		$this->applyCurlOptions( $curl, $curlOptions );
		$response		= $curl->exec( TRUE, TRUE );
		$this->curlInfo	= $curl->getInfo();
		$this->responseHeaders	= $curl->getHeaders();
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
	 *	@param		string|NULL			$key		Information Key, like http_code or content_type
	 *	@return		mixed
	 */
	public function getCurlInfo( ?string $key = NULL ): mixed
	{
		if( !$this->curlInfo )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( NULL === $key || '' === $key )
			return $this->curlInfo;
		if( !array_key_exists( $key, $this->curlInfo ) )
			throw new InvalidArgumentException( 'Status Key "'.$key.'" is invalid.' );
		return $this->curlInfo[$key];
	}

	/**
	 *	Returns value of a response header field of last GET request, case-insensitive.
	 *	Values of repeated header fields are combined, separated by comma.
	 *	@access		public
	 *	@param		string		$key		Header field name, like Content-Type
	 *	@return		string|NULL
	 */
	public function getResponseHeader( string $key ): ?string
	{
		foreach( $this->responseHeaders as $name => $values )
			if( 0 === strcasecmp( (string) $name, $key ) )
				return implode( ', ', (array) $values );
		return NULL;
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
	public function post( string $url, array $data, array|HeaderSection $headers = [], array $curlOptions = [] ): Response
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers ){
			if( $headers instanceof HeaderSection )
				$header	= $headers;
			else
				$header	= HeaderSection::getInstance()->addFieldPairs( $headers );
			$curlOptions[CURLOPT_HTTPHEADER]	= array_map( static fn( $field ) => $field->toString(), $header->getFields() );
		}
		$this->applyCurlOptions( $curl, $curlOptions );

		//  cURL hack (file upload identifier)
		foreach( $data as $key => $value )
			//  leading @ in field values
			if( is_string( $value ) && substr( $value, 0, 1 ) == "@" )
				//  need to be escaped
				$data[$key]	= "\\".$value;

		$curl->setOption( CURLOPT_POST, TRUE );
		$curl->setOption( CURLOPT_POSTFIELDS, http_build_query( $data, '', "&" ) );

		$response		= $curl->exec( TRUE, FALSE );
		$this->curlInfo	= $curl->getInfo();
		return ResponseParser::fromString( $response );
	}

	/**
	 *	Set Username and Password for Basic Auth.
	 *	@access		public
	 *	@param		string		$username	Basic Auth Username
	 *	@param		string		$password	Basic Auth Password
	 *	@return		static
	 */
	public function setBasicAuth( string $username, string $password ): static
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
	 *	@return		static
	 *	@link		https://www.php.net/manual/en/function.curl-setopt.php
	 */
	public function setCurlOption( int $key, mixed $value ): static
	{
		$this->curl->setOption( $key, $value );
		return $this;
	}

	/**
	 *	Sets Type of HTTP Compression (Encoding).
	 *	@access		public
	 *	@param		string		$method		Compression Type (gzip|deflate)
	 *	@return		static
	 */
	public function setEncoding( string $method ): static
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
	 *	@return		static
	 */
	public function setProxy( string $address, int $type = CURLPROXY_HTTP, ?string $auth = NULL ): static
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
	 *	@return		static
	 */
	public function setUserAgent( string $string ): static
	{
		if( empty( $string ) )
			throw new InvalidArgumentException( 'Must be set' );
		$this->curl->setOption( CURLOPT_USERAGENT, $string );
		return $this;
	}

	/**
	 *	Sets up SSL Verification.
	 *	Without calling this method, the defaults of cURL apply (certificate and host are verified).
	 *	CURLOPT_SSL_VERIFYHOST is set to 2 (TRUE) or 0 (FALSE).
	 *	@access		public
	 *	@param		boolean			$host		Flag: verify Host
	 *	@param		boolean|integer	$peer		Flag: verify Peer
	 *	@param		string|NULL		$caPath		Path to certificates
	 *	@param		string|NULL		$caInfo		Certificate File Name
	 *	@return		static
	 */
	public function setVerify( bool $host = FALSE, bool|int $peer = FALSE, ?string $caPath = NULL, ?string $caInfo = NULL ): static
	{
		$this->curl->setOption( CURLOPT_SSL_VERIFYHOST, $host ? 2 : 0 );
		$this->curl->setOption( CURLOPT_SSL_VERIFYPEER, (bool) $peer );
		$this->curl->setOption( CURLOPT_CAPATH, $caPath );
		$this->curl->setOption( CURLOPT_CAINFO, $caInfo );
		return $this;
	}
}

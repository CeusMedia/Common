<?php
/**
 *	Reader for HTTP Resources.
 *
 *	Copyright (c) 2010 Christian Würker (ceus-media.de)
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
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
/**
 *	Handler for HTTP Requests.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@extends		ADT_List_Dictionary
 *	@uses			Net_HTTP_Header_Field
 *	@uses			Net_HTTP_Header_Section
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.1
 *	@version		$Id$
 */
class Net_HTTP_Reader
{
	protected $curl;
	protected $curlInfo	= array();

	/**
	 *	Constructor, sets up cURL.
	 *	@access		public
	 *	@param		string		$httpVersion		HTTP Version, 1.0 by default
	 */
	public function __construct( $httpVersion = NULL )
	{
		$this->curl		= new Net_CURL;
		$this->curl->setOption( CURLOPT_ENCODING, '' );
		$this->curl->setOption( CURLOPT_HTTP_VERSION, $httpVersion );
	}

	/**
	 *	Applied cURL Options to a cURL Object.
	 *	@access		protected
	 *	@param		Net_CURL	$curl				cURL Object
	 *	@param		array		$options			Map of cURL Options
	 *	@return		void
	 */
	protected function applyCurlOptions( Net_CURL $curl, $options = array() )
	{
		foreach( $options as $key => $value )
		{
			if( is_string( $key ) )
			{
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
	 *	@param		string							$url			Resource URL
	 *	@param		array|Net_HTTP_Header_Section	$headers		Map of HTTP Header Fields or Header Section Object
	 *	@param		array							$curlOptions	Map of cURL Options
	 *	@return		Net_HTTP_Response
	 */
	public function get( $url, $headers = array(), $curlOptions = array() )
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers )
		{
			if( $headers instanceof Net_HTTP_Header_Section )
				$headers	= $headers->toArray();
			$curlOptions[CURLOPT_HTTPHEADER]	= $headers;
		}
		$this->applyCurlOptions( $curl, $curlOptions );
		$response		= $curl->exec( TRUE, FALSE );
		$this->curlInfo	= $curl->getInfo();
		$response		= Net_HTTP_Response_Parser::fromString( $response );
/*		$encodings	= $response->headers->getField( 'content-encoding' );
		while( $encoding = array_pop( $encodings ) )
		{
			$decompressor	= new Net_HTTP_Response_Decompressor;
			$type			= $encoding->getValue();
			$body			= $decompressor->decompressString( $response->getBody(), $type );
		}
		$response->setBody( $body );*/
		return $response;
	}

	/**
	 *	Returns Info Array or single Information from last cURL Request.
	 *	@access		public
	 *	@param		string		$key		Information Key
	 *	@return		mixed
	 */
	public function getCurlInfo( $key = NULL )
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
	 *	Posts Data to Resource and returns Response.
	 *	@access		public
	 *	@param		string							$url			Resource URL
	 *	@param		array							$data			Map of POST Data
	 *	@param		array|Net_HTTP_Header_Section	$headers		Map of HTTP Header Fields or Header Section Object
	 *	@param		array							$curlOptions	Map of cURL Options
	 *	@return		Net_HTTP_Response
	 */
	public function post( $url, $data, $headers, $curlOptions = array() )
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers )
		{
			if( $headers instanceof Net_HTTP_Header_Section )
				$headers	= $headers->toArray();
			$curlOptions[CURLOPT_HTTPHEADER]	= $headers;
		}
		$this->applyCurlOptions( $curl, $curlOptions );

		foreach( $data as $key => $value )															//  cURL hack (file upload identifier)
			if( is_string( $value ) && substr( $value, 0, 1 ) == "@" )								//  leading @ in field values
				$data[$key]	= "\\".$value;															//  need to be escaped
		$curl->setOption( CURLOPT_POST, TRUE );
		$curl->setOption( CURLOPT_POSTFIELDS, http_build_query( $data ) );

		$response		= $curl->exec( TRUE, FALSE );
		$this->curlInfo	= $curl->getInfo();
		$response		= Net_HTTP_Response_Parser::fromString( $response );
		return $response;
	}

	/**
	 *	Set Username and Password for Basic Auth.
	 *	@access		public
	 *	@param		string		$username	Basic Auth Username
	 *	@param		string		$password	Basic Auth Password
	 *	@return		void
	 */
	public function setBasicAuth( $username, $password )
	{
		$this->curl->setOption( CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
		$this->curl->setOption( CURLOPT_USERPWD, $username.":".$password );
	}

	/**
	 *	Sets a cURL Option for all Requests.
	 *	@access		public
	 *	@param		integer		$key		Constant Value of cURL Option
	 *	@param		mixed		$value		Option Value
	 *	@return		void
	 *	@link		http://www.php.net/manual/en/function.curl-setopt.php
	 */
	public function setCurlOption( $key, $value )
	{
		$this->curl->setOption( $key, $value );
	}

	/**
	 *	Sets Type of HTTP Compression (Encoding).
	 *	@access		public
	 *	@return		void
	 *	@param		string		$method		Compression Type (gzip|deflate)
	 *	@return		void
	 */
	public function setEncoding( $method )
	{
		$this->curl->setOption( CURLOPT_ENCODING, $method );
	}

	/**
	 *	Sets proxy domain or IP.
	 *	@access		public
	 *	@param		string		$address	Domain or IP (and Port) of proxy server
	 *	@param		integer		$type		Type of proxy server (CURLPROXY_HTTP | CURLPROXY_SOCKS5 )
	 *	@param		string		$auth		Username and password for proxy authentification
	 *	@return		void
	 */
	public function setProxy( $address, $type = CURLPROXY_HTTP, $auth = NULL )
	{
		$this->curl->setOption( CURLOPT_HTTPPROXYTUNNEL, TRUE );
		$this->curl->setOption( CURLOPT_PROXY, $address );
		$this->curl->setOption( CURLOPT_PROXYTYPE, $type );
		if( $auth )
			$this->curl->setOption( CURLOPT_PROXYUSERPWD, $auth );
	}

	/**
	 *	Sets User Agent.
	 *	@access		public
	 *	@param		string		$string		User Agent to set
	 *	@return		void
	 */
	public function setUserAgent( $string )
	{
		if( empty( $string ) )
			throw new InvaligArgumentException( 'Must be set' );
		$this->curl->setOption( CURLOPT_USERAGENT, $string );
	}

	/**
	 *	Sets up SSL Verification.
	 *	@access		public
	 *	@param		boolean		$host		Flag: verify Host
	 *	@param		integer		$peer		Flag: verify Peer
	 *	@param		string		$caPath		Path to certificates
	 *	@param		string		$caInfo		Certificate File Name
	 *	@return		void
	 */
	public function setVerify( $host = FALSE, $peer = 0, $caPath = NULL, $caInfo = NULL )
	{
		$this->curl->setOption( CURLOPT_SSL_VERIFYHOST, $host );
		$this->curl->setOption( CURLOPT_SSL_VERIFYPEER, $peer );
		$this->curl->setOption( CURLOPT_CAPATH, $caPath );
		$this->curl->setOption( CURLOPT_CAINFO, $caInfo );
	}
}
?>

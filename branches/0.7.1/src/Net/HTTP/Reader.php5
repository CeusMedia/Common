<?php
class Net_HTTP_Reader
{
	protected $curl;
	protected $curlInfo	= array();

	public function __construct( $httpVersion = NULL )
	{
		$this->curl		= new Net_CURL;
		$this->curl->setOption( CURLOPT_ENCODING, '' );
		$this->curl->setOption( CURLOPT_HTTP_VERSION, $httpVersion );
	}

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

	public function get( $url, $headers = array(), $curlOptions = array() )
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers )
		{
			if( $headers instanceof Net_HTTP_Headers )
				$headers	= $headers->toArray();
			$curlOptions[CURLOPT_HTTPHEADER]	= $headers;
		}
		$this->applyCurlOptions( $curl, $curlOptions );
		$response		= $curl->exec( TRUE, FALSE );
		$this->curlInfo	= $curl->getInfo();
		$response		= Net_HTTP_Response_Parser::fromString( $response );
/*		$encodings	= $response->headers->getHeader( 'content-encoding' );
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
	 *	Returns Status Array or single Status Information from last Request.
	 *	@access		public
	 *	@param		string		$key		Status Information Key
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

	public function post( $url, $data, $headers, $curlOptions = array() )
	{
		$curl	= clone( $this->curl );
		$curl->setOption( CURLOPT_URL, $url );
		if( $headers )
		{
			if( $headers instanceof Net_HTTP_Headers )
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

	public function setCurlOption( $key, $value )
	{
		$this->curl->setOption( $key, $value );
	}

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

	public function setVerify( $host = FALSE, $peer = 0, $caPath = NULL, $caInfo = NULL )
	{
		$this->curl->setOption( CURLOPT_SSL_VERIFYHOST, $host );
		$this->curl->setOption( CURLOPT_SSL_VERIFYPEER, $peer );
		$this->curl->setOption( CURLOPT_CAPATH, $caPath );
		$this->curl->setOption( CURLOPT_CAINFO, $caInfo );
	}
}
?>

<?php
/**
 *	Reader for Contents from the Net.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net
 *	@uses			Net_CURL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
/**
 *	Reader for Contents from the Net.
 *
 *	@category		cmClasses
 *	@package		Net
 *	@uses			Net_CURL
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2008
 *	@version		$Id$
 */
class Net_Reader
{
	protected $body				= NULL;
	protected $headers			= array();
	/**	@var		array		$info			Map of information of last request */
	protected $info				= array();
	/**	@var		string		$url			URL to read */
	protected $url;
	/**	@var		string		$agent			User Agent */
	protected static $userAgent	= "cmClasses:Net_Reader/0.7";
	/**	@var		string		$username		Username for Basic Authentication */
	protected $username			= "";
	/**	@var		string		$password		Password for Basic Authentication */
	protected $password			= "";
	/**	@var		boolean		$verifyHost		Flag: verify Host */
	protected $verifyHost 		= FALSE;
	/**	@var		boolean		$verifyPeer		Flag: verify Peer */
	protected $verifyPeer		= FALSE;
	/**	@var		string		$proxyAddress	Domain or IP (and port) of proxy server */
	protected $proxyAddress		= NULL;
	/**	@var		string		$proxyAuth		Username and password for proxy server authentification */
	protected $proxyAuth		= 80;
	/**	@var		integer		$proxyType		Type of proxy server (CURLPROXY_HTTP | CURLPROXY_SOCKS5) */
	protected $proxyType		= CURLPROXY_HTTP;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url			URL to read
	 *	@return		void
	 */
	public function __construct( $url = NULL )
	{
		if( $url )
			$this->setUrl( $url );
	}

	public function getBody()
	{
		if( !$this->info )
			throw new RuntimeException( "No Request has been sent, yet." );
		return $this->body;
	}

	/**
	 *	Returns Headers Array or a specified header from last request.
	 *	@access		public
	 *	@param		string		$key		Header key
	 *	@return		mixed
	 */
	public function getHeader( $key = NULL )
	{
		if( !$this->info )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->headers;
		if( !array_key_exists( $key, $this->headers ) )
			throw new InvalidArgumentException( 'Header Key "'.$key.'" is invalid.' );
		return $this->headers[$key];
	}

	/**
	 *	Returns information map or single information from last request.
	 *	@access		public
	 *	@param		string		$key		Information key
	 *	@return		mixed
	 */
	public function getInfo( $key = NULL )
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
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 *	Returns set user agent.
	 *	@access		public
	 *	@return		string
	 */
	public function getUserAgent()
	{
		return self::$userAgent;
	}

	/**
	 *	Requests set URL and returns response.
	 *	@access		public
	 *	@return		string		$curlOptions		Map of cURL options
	 *	@todo		Auth
	 */
	public function read( $curlOptions = array() )
	{
		$curl		= new Net_CURL( $this->url );

		$curl->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		$curl->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
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

		foreach( $curlOptions as $key => $value )
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
		$result			= $curl->exec( TRUE, FALSE );
		$response		= Net_HTTP_Response_Parser::fromString( $result );

		$this->body		= $response->getBody();
		$this->headers	= $response->getHeaders();
		$this->info		= $curl->getInfo();
		$code			= $curl->getInfo( Net_CURL::INFO_HTTP_CODE );
		$error			= $curl->getInfo( Net_CURL::INFO_ERROR );
		$errno			= $curl->getInfo( Net_CURL::INFO_ERRNO );
		if( $errno )
			throw new Exception_IO( 'HTTP request failed: '.$error, $errno, $this->url );
		if( !in_array( $code, array( '200', '301', '303', '304', '307' ) ) )
			throw new Exception_IO( 'HTTP request failed', $code, $this->url );
		return $this->body;
	}

	/**
	 *	Requests URL and returns Response statically.
	 *	@access		public
	 *	@static
	 *	@param		string		$url		URL to request
	 *	@param		array		$curlOptions	Array of cURL Options
	 *	@return		string
	 *	@todo		Auth
	 */
	public static function readUrl( $url, $curlOptions = array() )
	{
		$reader	= new Net_Reader( $url );
		return $reader->read( $curlOptions );
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
		$this->username	= $username;
		$this->password	= $password;
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
		$this->proxyAddress	= $address;
		$this->proxyType	= $type;
		$this->proxyAuth	= $auth;
	}

	/**
	 *	Sets User Agent.
	 *	@access		public
	 *	@param		string		$title		User Agent to set
	 *	@return		void
	 */
	public function setUserAgent( $title )
	{
		self::$userAgent	= $title;
	}

	/**
	 *	Set URL to request.
	 *	@access		public
	 *	@param		string		$url		URL to request
	 *	@return		void
	 */
	public function setUrl( $url )
	{
		if( !( is_string( $url ) && $url ) )
			throw new InvalidArgumentException( "No URL given." );
		$this->url	= $url;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYHOST.
	 *	@access		public
	 *	@param		bool		$verify		Flag: verify Host
	 *	@return		void
	 */
	public function setVerifyHost( $verify )
	{
		$this->verifyHost	= (bool) $verify;
	}

	/**
	 *	Sets Option CURLOPT_SSL_VERIFYPEER.
	 *	@access		public
	 *	@param		bool		$verify		Flag: verify Peer
	 *	@return		void
	 */
	public function setVerifyPeer( $verify )
	{
		$this->verifyPeer	= (bool) $verify;
	}
}
?>

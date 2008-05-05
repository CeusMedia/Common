<?php
import( 'de.ceus-media.net.cURL' );
/**
 *	Reader for Contents from the Net.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
/**
 *	Reader for Contents from the Net.
 *	@package		net
 *	@uses			Net_cURL
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2008
 *	@version		0.6
 */
class Net_Reader
{
	/**	@var		array		$status			Status Array of last Request */
	protected $status			= array();
	/**	@var		string		$url			URL to read */
	protected $url;
	/**	@var		string		$agent			User Agent */
	protected static $userAgent	= "cmClasses:Net_Reader/0.6";
	/**	@var		string		$username		Username for Basic Authentication */
	private $username			= "";
	/**	@var		string		$password		Password for Basic Authentication */
	private $password			= "";
	/**	@var		bool		$verifyHost		Flag: verify Host */
	private $verifyHost 		= false;
	/**	@var		bool		$verifyPeer		Flag: verify Peer */
	private $verifyPeer			= false;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$url		URL to read
	 *	@return		void
	 */
	public function __construct( $url )
	{
		$this->setUrl( $url );
	}

	/**
	 *	Returns Status Array of single Status Information from last Request.
	 *	@access		public
	 *	@param		string		$key		Status Information Key
	 *	@return		void
	 */
	public function getStatus( $key = NULL )
	{
		if( !$this->status )
			throw new RuntimeException( "No Request has been sent, yet." );
		if( !$key )
			return $this->status;
		if( !array_key_exists( $key, $this->status ) )
			throw new InvalidArgumentException( 'Status Key "'.$key.'" is invalid.' );
		return $this->status[$key];
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
	 *	Returns set User Agent.
	 *	@access		public
	 *	@return		string
	 */
	public function getUserAgent()
	{
		return self::$userAgent;
	}

	/**
	 *	Requests set URL and returns Response.
	 *	@access		public
	 *	@return		string
	 *	@todo		Auth
	 */
	public function read( $curlOptions = array() )
	{
		$curl		= new Net_cURL( $this->url );

		if( self::$userAgent )
			$curl->setOption( CURLOPT_USERAGENT, self::$userAgent );
		if( $this->username )
			$curl->setOption( CURLOPT_USERPWD, $this->username.":".$this->password );
		if( $this->verifyHost )
			$curl->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		if( $this->verifyPeer )
			$curl->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
		foreach( $curlOptions as $key => $value )
		{
			if( is_string( $key ) && preg_match( "@^CURLOPT_@", $key ) )
				$key	= eval( "return ".$key.";" );
			$curl->setOption( $key, $value );
		}
		$response		= $curl->exec();
		$this->status	= $curl->getStatus();
		$code			= $curl->getStatus( CURL_STATUS_HTTP_CODE );
	
		if( !in_array( $code, array( '200', '304' ) ) )
			throw new RuntimeException( 'URL "'.$this->url.'" can not be accessed (HTTP Code '.$code.').', $code );

		return $response;
	}

	/**
	 *	Requests URL and returns Response statically.
	 *	@access		public
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
		if( !$url )
			throw new RuntimeException( "No URL set." );
		$this->url	= $url;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYHOST.
	 *	@access		public
	 *	@param		bool		$verify		Flag: verify Host
	 *	@return		void
	 */
	public function setVerifyHost( $verify )
	{
		$this->verifyHost	= (bool) $verify;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYPEER.
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
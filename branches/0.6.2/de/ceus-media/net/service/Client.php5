<?php
/**
 *	Client for interaction with Frontend Services.
 *	@package		net.service
 *	@uses			Net_cURL
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2007
 *	@version		0.6
 */
/**
 *	Client for interaction with Frontend Services.
 *	@package		net.service
 *	@uses			Net_cURL
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2007
 *	@version		0.6
 */
class Net_Service_Client
{
	/**	@var		string		$id					ID of Service Request Client */
	protected $id;
	/**	@var		bool		$logFile			File Name of Request Log File */
	protected $logFile			= NULL;
	/**	@var		string		$host				Basic URL of Services Host */
	protected $host;
	/**	@var		string		$username			Username for Basic Authentication */
	protected $username			= "";
	/**	@var		string		$password			Password for Basic Authentication */
	protected $password			= "";
	/**	@var		string		$userAgent			User Agent to sent to Service Point */
	protected $userAgent;
	/**	@var		bool		$verifyHost			Flag: verify Host */
	protected $verifyHost 		= FALSE;
	/**	@var		bool		$verifyPeer			Flag: verify Peer */
	protected $verifyPeer		= FALSE;
	/**	@var		array		$requests			Collected Request Information */
	protected $requests			= array();	
	/**	@var		array		$statistics			Collected Statistic Information */
	protected $statistics		= array(
		'requests'	=> 0,
		'traffic'	=> 0,
		'time'		=> 0,
	);
	/**	@var		array		$compressionTypes	List of supported Compression Types */
	protected $compressionTypes	= array(
		'deflate',
		'gzip',
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$hostUrl			Basic Host URL of Service
	 *	@param		bool		$logFile			File Name of Request Log File
	 *	@return		void
	 */
	public function __construct( $hostUrl = NULL, $logFile = NULL )
	{
		$this->id	= md5( uniqid( rand(), true ) );
		if( $hostUrl )
			$this->setHostAddress( $hostUrl );
		if( $logFile )
			$this->setLogFile( $logFile );
	}

	/**
	 *	Decodes Response if JSON oder PHP Serial.
	 *	@access		protected
	 *	@param		string		$response			Response Content as serialized String
	 *	@param		string		$format				Format of Serial (json|php|wddx|xml|rss|txt|...)
	 *	@return		mixed
	 */
	protected function decodeResponse( $response, $format )
	{
		//  --  CHECK EXCEPTION  --  //
		ob_start();
		$exception	= unserialize( $response );
		ob_get_clean();
		if( $exception && $exception instanceof Exception )
			throw $exception;

		//  --  DECODE SERIALS  --  //
		ob_start();
		switch( $format )
		{
			case 'json':
				$response	= json_decode( $response );
				break;
			case 'php':
				$response	= unserialize( $response );
				break;
			case 'wddx':
				$response	= wddx_deserialize( $response );
				break;
			default:
				break;
		}
		$output	= ob_get_clean();
		if( $response === FALSE )
			return $output;
		return $response;
	}

	/**
	 *	Executes Request, logs statistical Information and returns Response.
	 *	@access		protected
	 *	@param		Net_cURL	$request			Request Object
	 *	@param		bool		$compression		Type of Compression of Content (deflate,gzip)
	 *	@return		string
	 */
	protected function executeRequest( $request, $compression = NULL )
	{
		$request->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
		$request->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		if( $this->userAgent )
			$request->setOption( CURLOPT_USERAGENT, $this->userAgent );
		if( $this->username )
			$request->setOption( CURLOPT_USERPWD, $this->username.":".$this->password );
		$response['content']	= $request->exec();
		$response['status']		= $request->getStatus();
		$response['headers']	= $request->getHeader();
	
		$code		= $request->getStatus( CURL_STATUS_HTTP_CODE );
		if( !in_array( $code, array( '200', '304' ) ) )
			throw new RuntimeException( 'URL "'.$request->getOption( CURLOPT_URL ).'" can not be accessed (HTTP Code '.$code.').', $code );

		if( array_key_exists( 'Content-Encoding', $response['headers'] ) )
		{
			$compression	= $response['headers']['Content-Encoding'][0];		
			if( !in_array( $compression, $this->compressionTypes ) )
				$compression	= $this->compressionTypes[0];
			$response['content']	= self::uncompressResponse( $response['content'], $compression );
		}
		return $response;
	}

	/**
	 *	Requests Information from Service.
	 *	@access		public
	 *	@param		string		$service			Name of Service
	 *	@param		string		$format				Response Format
	 *	@param		array		$parameters			Array of URL Parameters
	 *	@param		bool		$verbose			Flag: show Request URL and Response
	 *	@return		mixed
	 */
	public function get( $service, $format = NULL, $parameters = array(), $verbose = FALSE )
	{
		import( 'de.ceus-media.net.cURL' );
		import( 'de.ceus-media.StopWatch' );
		$baseUrl	= $this->host."?service=".$service."&format=".$format;
		$compress	= isset( $parameters['compressResponse'] ) ? strtolower( $parameters['compressResponse'] ) : "";
		$parameters	= array_merge( $parameters, array( 'clientRequestSessionId' => $this->id ) );
		$parameters	= "&".http_build_query( $parameters, '', '&' );
		$serviceUrl	= $baseUrl.$parameters;
		if( $verbose )
			remark( "GET: ".$serviceUrl );

		$st			= new StopWatch;
		$request	= new Net_cURL( $serviceUrl );
		$response	= $this->executeRequest( $request, $compress );
		if( $this->logFile )
		{
			$message	= time()." ".strlen( $response['content'] )." ".$st->stop( 6, 0 )." ".$service."\n";
			error_log( $message, 3, $this->logFile );
		}
		
		$this->requests[]	= array(
			'method'	=> "GET",
			'url'		=> $serviceUrl,
			'headers'	=> $response['headers'],
			'status'	=> $response['status'],
			'response'	=> $response['content'],
			'time'		=> $st->stop(),
		);
		$response['content']	= $this->decodeResponse( $response['content'], $format, $verbose );
		return $response['content'];
	}
	
	/**
	 *	Returns ID of Service Request Client.
	 *	@access		public
	 *	@return		string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 *	Returns noted Requests.
	 *	@access		public
	 *	@return		array
	 */
	public function getRequests()
	{
		return $this->requests;
	}

	/**
	 *	Send Information to Service.
	 *	@access		public
	 *	@param		string		$service			Name of Service
	 *	@param		string		$format				Response Format
	 *	@param		array		$data				Array of Information to post
	 *	@param		bool		$verbose			Flag: show Request URL and Response
	 *	@return		mixed
	 */
	public function post( $service, $format = NULL, $data = array(), $verbose = FALSE )
	{
		import( 'de.ceus-media.net.cURL' );
		import( 'de.ceus-media.StopWatch' );
		$baseUrl	= $this->host."?service=".$service."&format=".$format;
		if( $verbose )
			remark( "POST: ".$baseUrl );

		//  cURL POST Hack (cURL identifies leading @ in Values as File Upload  //
		foreach( $data as $key => $value )
			if( substr( $value, 0, 1 ) == "@" )
				$data[$key]	= "\\".$value;

		$data['clientRequestSessionId']	= $this->id;							//  adding Client Request Session ID

		$st			= new StopWatch;
		$request	= new Net_cURL( $baseUrl );
		$request->setOption( CURLOPT_POST, 1);
		$request->setOption( CURLOPT_POSTFIELDS, $data );
		$response	= $this->executeRequest( $request );
		if( $this->logFile )
		{
			$message	= time()." ".strlen( $response['content'] )." ".$st->stop( 6, 0 )." ".$service."\n";
			error_log( $message, 3, $this->logFile );
		}
		$this->requests[]	= array(
			'method'	=> "POST",
			'url'		=> $baseUrl,
			'data'		=> serialize( $data ),
			'headers'	=> $response['headers'],
			'status'	=> $response['status'],
			'response'	=> $response['content'],
			'time'		=> $st->stop(),
			);
		if( $verbose )
			xmp( $response );
		$response['content']	= $this->decodeResponse( $response['content'], $format, $verbose );
		return $response['content'];
	}

	/**
	 *	Sets HTTP Basic Authentication.
	 *	@access		public
	 *	@param		string		$username			Username for HTTP Basic Authentication.
	 *	@param		string		$password			Password for HTTP Basic Authentication.
	 *	@return		void
	 */
	public function setBasicAuth( $username, $password )
	{
		$this->username	= $username;
		$this->password	= $password;
	}

	/**
	 *	Sets Basic Host URL of Service.
	 *	@access		public
	 *	@param		string		$hostUrl			Basic Host URL of Service
	 *	@return		void
	 */
	public function setHostAddress( $hostUrl )
	{
		$this->host	= $hostUrl;
	}

	/**
	 *	Sets File Name of Request Log File.
	 *	@access		public
	 *	@param		string		$fileName			File Name of Request Log File
	 *	@return		void
	 */
	public function setLogFile( $fileName )
	{
		$this->logFile	= $fileName;
	}

	/**
	 *	Sets Option CURL_USERAGENT.
	 *	@access		public
	 *	@param		int			$userAgent			User Agent to set
	 *	@return		void
	 */
	public function setUserAgent( $userAgent )
	{
		$this->userAgent	= $userAgent;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYHOST.
	 *	@access		public
	 *	@param		bool		$verify				Flag: verify Host
	 *	@return		void
	 */
	public function setVerifyHost( $verify )
	{
		$this->verifyHost	= (bool) $verify;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYPEER.
	 *	@access		public
	 *	@param		bool		$verify				Flag: verify Peer
	 *	@return		void
	 */
	public function setVerifyPeer( $verify )
	{
		$this->verifyPeer	= (bool) $verify;
	}

	/**
	 *	Decompresses compressed Response.
	 *	@access		protected
	 *	@param		string		$content			Response Content, compressed
	 *	@param		string		$type				Compression Type used for compressing Response
	 *	@return		string
	 */
	protected static function uncompressResponse( $content, $type )
	{
		switch( $type )
		{
			case 'deflate':
				$compressed	= @gzuncompress( $content );
				if( $compressed !== FALSE )
					$content	= $compressed;
				break;
			case 'gzip':
				$compressed	= @gzdecode( $content );
				if( $compressed !== FALSE )
					$content	= $compressed;
				break;
		}
		return $content;
	}
}
if( !function_exists( 'gzdecode' ) )
{
	function gzdecode( $data )
	{
		$flags	= ord( substr( $data, 3, 1 ) );
		$headerlen		= 10;
		$extralen		= 0;
		if( $flags & 4 )
		{
			$extralen	= unpack( 'v' ,substr( $data, 10, 2 ) );
			$extralen	= $extralen[1];
			$headerlen	+= 2 + $extralen;
		}
		if( $flags & 8 ) 												// Filename
			$headerlen = strpos( $data, chr( 0 ), $headerlen ) + 1;
		if( $flags & 16 )												// Comment
			$headerlen = strpos( $data, chr( 0 ), $headerlen ) + 1;
		if( $flags & 2 )												// CRC at end of file
			$headerlen	+= 2;
		$unpacked = gzinflate( substr( $data, $headerlen ) );
		if( $unpacked === FALSE )
			$unpacked = $data;
		return $unpacked;
	}
}
?>
<?php
/**
 *	Client for interaction with Frontend Services.
 *	@package		service
 *	@uses			Net_cURL
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2007
 *	@version		0.3
 */
/**
 *	Client for interaction with Frontend Services.
 *	@package		service
 *	@uses			Net_cURL
 *	@uses			StopWatch
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			02.07.2007
 *	@version		0.3
 */
class Service_Client
{
	/**	@var		string		$id				ID of Service Request Client */
	private $id;
	/**	@var		bool		$useLogFile		Flag: use Log File */
	private $useLogFile			= false;
	/**	@var		string		$host			Basic URL of Services Host */
	private $host;
	/**	@var		string		$username		Username for Basic Authentication */
	private $username			= "";
	/**	@var		string		$password		Password for Basic Authentication */
	private $password			= "";
	/**	@var		string		$userAgent		User Agent to sent to Service Point */
	private $userAgent;
	/**	@var		bool		$verifyPeer		Flag: verify Peer */
	private $verifyPeer			= false;
	/**	@var		bool		$verifyHost		Flag: verify Host */
	private $verifyHost 		= false;
	/**	@var		array		$requests		Collected Request Information */
	private $requests			= array();
	/**	@var		array		$statistics		Collected Statistic Information */
	private $statistics			= array(
		'requests'	=> 0,
		'traffic'	=> 0,
		'time'		=> 0,
	);

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$hostUrl		Basic Host URL of Service
	 *	@param		bool		$useLogFile		Flag: use Service Log
	 *	@return		void
	 */
	public function __construct( $hostUrl = "", $useLogFile = false )
	{
		$this->id	= md5( uniqid( rand(), true ) );
		if( $hostUrl )
			$this->setHostAddress( $hostUrl );
		if( $useLogFile )
			$this->useLogFile = true;
	}

	/**
	 *	Decodes Response if JSON oder PHP Serial.
	 *	@access		protected
	 *	@param		string		JSON or PHP Serial
	 *	@param		string		Format of Serial
	 *	@return		mixed
	 */
	protected function decodeResponse( $response, $format, $verbose = false )
	{
		//  --  CHECK EXCEPTION  --  //
		ob_start();
		$exception	= unserialize( $response );
		ob_get_clean();
		if( $exception && $exception instanceof Exception )
			throw $exception;

		//  --  DECODE SERIALS  --  //
		if( strtolower( $format ) == "json" )
		{
			return json_decode( $response );
		}
		else if( strtolower( $format ) == "php" )
		{
//			xmp( $response );
			return unserialize( $response );
			ob_start();
			$response	= unserialize( $response );
			$output	= ob_end_clean();
			if( $response === false )
				return $output;
		}
		return $response;
	}

	/**
	 *	Executes Request, logs statistical Information and returns Response.
	 *	@access		protected
	 *	@param		Net_cURL	$request		Request Object
	 *	@param		bool		$uncompress		Flag: uncompress with GZIP
	 *	@return		string
	 */
	protected function executeRequest( $request, $uncompress = false )
	{
		$request->setOption( CURLOPT_SSL_VERIFYPEER, $this->verifyPeer );
		$request->setOption( CURLOPT_SSL_VERIFYHOST, $this->verifyHost );
		if( $this->userAgent )
			$request->setOption( CURLOPT_USERAGENT, $this->userAgent );
		if( $this->username )
			$request->setOption( CURLOPT_USERPWD, $this->username.":".$this->password );
		$response	= $request->exec();
		$response	= $uncompress ? gzuncompress( $response ) : $response;
		return $response;
	}

	/**
	 *	Requests Information from Service.
	 *	@access		public
	 *	@param		string		$service		Name of Service
	 *	@param		string		$format			Response Format
	 *	@param		array		$parameters		Array of URL Parameters
	 *	@param		bool		$verbose		Flag: show Request URL and Response
	 *	@return		mixed
	 */
	public function get( $service, $format, $parameters = array(), $verbose = false )
	{
		import( 'de.ceus-media.net.cURL' );
		import( 'de.ceus-media.StopWatch' );
		$baseURL	= $this->host."?service=".$service."&format=".$format;
		$compress	= array_key_exists( 'compressResponse', $parameters ) && $parameters['compressResponse'];
		$parameters	= "&".http_build_query( $parameters, '', '&' );
		$serviceURL	= $baseURL.$parameters."&clientRequestSessionId=".$this->id;
		if( $verbose )
			remark( "GET: ".$serviceURL );
		$request	= new Net_cURL( $serviceURL );
		$st	= new StopWatch;
		$response	= $this->executeRequest( $request, $compress );
		if( $this->useLogFile )
		{
			$message	= time()." ".strlen( $response )." ".$st->stop( 6, 0 )." ".$service."\n";
			error_log( $message, 3, "logs/services.log" );
		}
		
		$this->requests[]	= array(
			'method'	=> "GET",
			'url'		=> $serviceURL,
			'response'	=> $response,
			'time'		=> $st->stop(),
			);
		$response	= $this->decodeResponse( $response, $format, $verbose );
		return $response;
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
	 *	@param		string		$service		Name of Service
	 *	@param		string		$format			Response Format
	 *	@param		array		$data			Array of Information to post
	 *	@param		bool		$verbose		Flag: show Request URL and Response
	 *	@return		mixed
	 */
	public function post( $service, $format, $data = array(), $verbose = false )
	{
		import( 'de.ceus-media.net.cURL' );
		import( 'de.ceus-media.StopWatch' );
		$baseURL	= $this->host."?service=".$service."&format=".$format;
		if( $verbose )
			remark( "POST: ".$baseURL );

		//  cURL POST Hack (cURL identifies leading @ in Values as File Upload  //
		foreach( $data as $key => $value )
			if( substr( $value, 0, 1 ) == "@" )
				$data[$key]	= "\\".$value;

		$data['clientRequestSessionId']	= $this->id;							//  adding Client Request Session ID

		$request	= new Net_cURL( $baseURL );
		$request->setOption( CURLOPT_POST, 1);
		$request->setOption( CURLOPT_POSTFIELDS, $data );
		$st	= new StopWatch;
		$response	= $this->executeRequest( $request );
		if( $this->useLogFile )
		{
			$message	= time()." ".strlen( $response )." ".$st->stop( 6, 0 )." ".$service."\n";
			error_log( $message, 3, "logs/services.log" );
		}
		$this->requests[]	= array(
			'method'	=> "POST",
			'url'		=> $baseURL,
			'data'		=> serialize( $data ),
			'response'	=> $response,
			'time'		=> $st->stop(),
			);
		if( $verbose )
			xmp( $response );
		$response	= $this->decodeResponse( $response, $format );
		return $response;
	}

	/**
	 *	Sets HTTP Basic Authentication.
	 *	@access		public
	 *	@param		string		$username		Username for HTTP Basic Authentication.
	 *	@param		string		$password		Password for HTTP Basic Authentication.
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
	 *	@param		string		$hostUrl		Basic Host URL of Service
	 *	@return		void
	 */
	public function setHostAddress( $hostUrl )
	{
		$this->host	= $hostUrl;
	}

	/**
	 *	Sets Option CURL_USERAGENT.
	 *	@access		public
	 *	@param		int			$userAgent		User Agent to set
	 *	@return		void
	 */
	public function setUserAgent( $userAgent )
	{
		$this->userAgent	= $userAgent;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYHOST.
	 *	@access		public
	 *	@param		bool		$verify			Flag: verify Host
	 *	@return		void
	 */
	public function setVerifyHost( $verify )
	{
		$this->verifyHost	= (bool) $verify;
	}

	/**
	 *	Sets Option CURL_SSL_VERIFYPEER.
	 *	@access		public
	 *	@param		bool		$verify			Flag: verify Peer
	 *	@return		void
	 */
	public function setVerifyPeer( $verify )
	{
		$this->verifyPeer	= (bool) $verify;
	}
}
?>
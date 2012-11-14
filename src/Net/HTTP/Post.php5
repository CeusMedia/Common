<?php
class Net_HTTP_Post {

	const TRANSPORT_NONE		= 0;
	const TRANSPORT_FOPEN		= 1;
	const TRANSPORT_CURL		= 2;
	
	protected $transport		= FALSE;
	protected $dataMaxLength	= 600000;
	static protected $userAgent	= "cmClasses:Net_HTTP_Post/0.7";									//  default user agent to report to server, can be overriden by constructor or given CURL options on get or post
	
	public function __construct(){
        $allowUrlFopen	= preg_match( '/1|yes|on|true/i', ini_get( 'allow_url_fopen' ) );
		if( Net_CURL::isSupported() )
			$this->transport	= self::TRANSPORT_CURL;
		else if( $allowUrlFopen )
			$this->transport	= self::TRANSPORT_FOPEN;
	}

	static public function sendData( $url, $data = array(), $curlOptions = array() ){
		$post	= new self();
		return $post->send( $url, $data, $curlOptions = array() );
	}
	
	public function send( $url, $data = array(), $curlOptions = array() ){
		if( is_array( $data ) )
			$data	= http_build_query( $data, NULL, '&' );
		if( strlen( $data ) > $this->dataMaxLength )
			throw new OutOfBoundsException( 'POST content larger than '.$this->dataMaxLength.' bytes' );
		$contentType	= 'Content-type: application/x-www-form-urlencoded';

		switch( $this->transport ){
			case self::TRANSPORT_CURL:
				$curl		= new Net_CURL( $url );
				$options	= array(
					CURLOPT_POST				=> TRUE,
					CURLOPT_RETURNTRANSFER		=> TRUE,
					CURLOPT_HTTPHEADER			=> array( $contentType ),
					CURLOPT_POSTFIELDS			=> $data,
					CURLOPT_FOLLOWLOCATION		=> FALSE,
					CURLOPT_USERAGENT			=> self::$userAgent,
					CURLOPT_CONNECTTIMEOUT		=> 15,
				) + $curlOptions;
				foreach( $options as $key => $value )
					$curl->setOption( $key, $value );
				return trim( $curl->exec( TRUE ) );

			case self::TRANSPORT_FOPEN:
				$stream	= array(
					'method'		=> 'POST',
					'header'		=> $contentType,
					'content'		=> $data,
					'max_redirects'	=> 0,
					'timeout'		=> 15,
				);
				$stream	= stream_context_create( array( 'http'	=> $stream ) );
	            return trim( file_get_contents( $url, FALSE, $stream ) );

			default:
				throw new RuntimeException( 'Could not make HTTP request: allow_url_open is false and cURL not available' );
		}
	}
}
?>

<?php
class Net_HTTP_Response_Sender
{
	public function  __construct( Net_HTTP_Response $response ) {
		$this->response	= $response;
	}

	public static function sendResponse( Net_HTTP_Response $response, $compression = NULL )
	{
		$sender	= new Net_HTTP_Response_Sender( $response );
		return $sender->send( $compression );
	}

	public function send( $compression = NULL, $sendLengthHeader = TRUE )
	{
		$length		= strlen( $this->response->getBody() );
		if( $compression )
		{
			$compressor	= new Net_HTTP_Response_Compressor;
			$compressor->compressResponse( $this->response, $compression, $sendLengthHeader );
			$lengthNow	= strlen( $this->response->getBody() );
			$ratio		= round( $lengthNow / $length * 100 );
			error_log( $compression.':'.$length."->".$lengthNow.":".$ratio."%\n", 3, getCwd().'/NetHttpResponseSender.log' );
		}
		else
			error_log( 'identity:'.$length.":".$this->response->getBody()."\n", 3, getCwd().'/NetHttpResponseSender.log' );

		foreach( $this->response->getHeaders() as $header )
			header( $header->toString() );
		print( $this->response->getBody() );
		flush();
		return strlen( $this->response->getBody() );
	}
}
?>

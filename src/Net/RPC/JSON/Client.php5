<?php
class Net_RPC_JSON_Client{

	public function __construct( $host, $path ){
		$this->host	= $host;
		$this->path	= $path;
	}

	public function request( $method, $arguments = array() ){
		if( !is_string( $method ) )
			throw new InvalidArgumentException ( "Method must be a string" );
		if( !trim( $method ) )
			throw new InvalidArgumentException ( "Method must not be empty" );
		$time0		= microtime( TRUE );
		$client		= new Net_HTTP_Request_Sender( $this->host, $this->path );
		$client->setRawData( json_encode( array( 'method' => $method, 'arguments' => $arguments ) ) );
		$clock		= new Alg_Time_Clock();
		$time1		= microtime( TRUE );
		$response	= $client->send();
		$time2		= microtime( TRUE );
		$message	= json_decode( $response->getBody() );
		$time3		= microtime( TRUE );
		if( !is_object( $message ) )
			throw new Exception_IO( 'Received data is not a valid JSON object' );
		$message->timeTransfer		= round( ( $time2 - $time1 ) * 1000000, 3 );
		$message->timeComplete		= round( ( $time3 - $time0 ) * 1000000, 3 );
		$message->timestampStart	= $time0;
		$message->timestampEncoded	= $time1;
		$message->timestampReceived	= $time2;
		$message->timestampDecoded	= $time3;
		return $message;
	}
}
?>

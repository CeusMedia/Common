<?php
class Net_RPC_JSON_Caller{
	
	protected $messages	= array();

	public function __construct( $host, $path ){
		$this->client	= new Net_RPC_JSON_Client( $host, $path );
	}
	
	public function __call( $method, $arguments ){
		$message	= $this->client->request( $method, $arguments );
		$this->messages[]	= $message;
		if( !empty( $message->stdout ) )
			print( $message->stdout );
		if( $message->status == 'error' ){
			if( !empty( $message->serial ) ){
				$e	= unserialize( $message->serial );
				throw $e;
			}
		}
		return $message->data;
	}

	public function getLast(){
		return array_pop( $this->getMessages() );
	}

	public function getMessages(){
		return $this->messages;
	}
}
?>
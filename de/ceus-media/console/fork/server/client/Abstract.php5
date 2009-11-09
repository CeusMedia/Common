<?php
abstract class Fork_Server_Client_Abstract
{
	protected $port		= NULL;

	public function __construct( $port = NULL )
	{
		if( !is_null( $port ) );
			$this->setPort( $port )
	}
	
	abstract function getRequest();
	
	protected function getResponse()
	{
		$socket = stream_socket_client( "tcp://127.0.0.1:".$this->port, $errno, $errstr, 30 );
		if( !$socket )
			die( $errstr.' ('.$errno.')<br />\n' );

		$request	= $this->getResponse();
		$buffer		= "";
		fwrite( $socket, $request );
		while( !feof( $socket ) )
			$buffer	.= fgets( $socket, 1024 );
		fclose( $socket );
		return $buffer;
	}

	public function setPort( $port )
	{
		$this->port	= $port;
	}
}
?>
<?php
class StandardConnector
{
	var $active_socket;

	function openSocket( $server, $port )
	{
		$this->active_socket = fsockopen( $server, $port );
		if( !$this->active_socket )
			return false;
		socket_set_blocking( $this->active_socket, 0 );
		socket_set_timeout( $this->active_socket, 31536000 );
		return true;
	}

	function closeSocket()
	{
		return fclose( $this->active_socket );
	}

	function writeToSocket( $data )
	{
		return fwrite( $this->active_socket, $data );
	}

	function readFromSocket( $chunksize )
	{
		set_magic_quotes_runtime( 0 );
		$buffer = fread( $this->active_socket, $chunksize );
		set_magic_quotes_runtime( get_magic_quotes_gpc() );
		return $buffer;
	}
}
?>
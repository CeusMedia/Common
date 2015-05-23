<?php
/**
 *	@category		cmClasses
 *	@package		Net.Socket.Stream
 */
/**
 *	@category		cmClasses
 *	@package		Net.Socket.Stream
 */
abstract class Net_Socket_Stream_Server
{
	const FORMAT_PHP		= 0;
	const FORMAT_JSON		= 1;

	protected $connections	= array();
	protected $socket		= NULL;
	protected $port;

	public function __construct( $port = 8000 )
	{
		try
		{
			$socket = stream_socket_server( "tcp://0.0.0.0:".$port, $errorNumber, $errorMessage );
			stream_set_blocking( $socket, 0 );
			if( !$socket )
				throw new RuntimeException( $errorMessage, $errorNumber );
			$this->socket	= $socket;
			$this->connections[] = $socket;
			$this->runService();
		}
		catch( Exception $e )
		{
			$this->handleServerException( $e );
		}
	}

	abstract protected function handleRequest( $connection, $request );

	protected function handleServerException( $e )
	{
		switch( get_class( $e ) )
		{
			default:
				$message	= "[".$e->getCode."] ".$e->getMessage()."\n";
				die( $message );
		}
	}

	protected function handleUncatchedAppException( $e )
	{
		echo "EXCEPTION: ".$e->getMessage()."\n";
		echo $e->getTraceAsString()."\n";
	}


	protected function runService()
	{
		while( 1 )
		{
			$read	= $this->connections;
			$_w		= NULL;
			$_e		= NULL;
			$mod_fd	= stream_select( $read, $_w, $_e, 5 );
			if( $mod_fd === FALSE )
				break;

			for( $i = 0; $i < $mod_fd; ++$i )
			{
				if( $read[$i] === $this->socket )
				{
					$connection	= stream_socket_accept( $this->socket );
#					fwrite( $connection, "Hello! The time is ".date( "n/j/Y g:i a" )."\n" );
					$this->connections[] = $connection;
				}
				else
				{
					$request = fread( $read[$i], 1024 );
					if( $request === FALSE )
					{
						echo "Something bad happened";
						$indexConnection = array_search( $read[$i], $this->connections, TRUE );
					}
					else if( strlen( $request ) === 0 )									// connection closed
					{
						fclose( $read[$i] );
						$indexConnection	= array_search( $read[$i], $this->connections, TRUE );
					}
					else
					{
#						echo "Received: ".serialize( $request )."\n";
						try
						{
							$this->handleRequest( $read[$i], $request );
						}
						catch( Exception $e )
						{
							$this->handleUncatchedAppException( $e );
						}
						$indexConnection	= array_search($read[$i], $this->connections );
					}
					unset( $this->connections[$indexConnection] );
				}
			}
		}
	}

	protected function sendResponse( $connection, Net_Socket_Stream_Package $response )
	{
		$serial	= $response->toSerial();
//		remark( "serial: ".$serial );
		fwrite( $connection, $serial );
		fclose( $connection );
	}
}
?>
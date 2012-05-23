<?php
/**
 *	...
 *
 *	Copyright (c) 2011-2012 Christian Würker (ceusmedia.com)
 *
 *	This program is free software: you can redistribute it and/or modify
 *	it under the terms of the GNU General Public License as published by
 *	the Free Software Foundation, either version 3 of the License, or
 *	(at your option) any later version.
 *
 *	This program is distributed in the hope that it will be useful,
 *	but WITHOUT ANY WARRANTY; without even the implied warranty of
 *	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *	GNU General Public License for more details.
 *
 *	You should have received a copy of the GNU General Public License
 *	along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *	@category		cmClasses
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
/**
 *	...
 *
 *	@category		cmClasses
 *	@package		Net.RPC.JSON
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2011-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			0.7.5
 *	@version		$Id$
 */
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
			throw new Exception_IO( 'Received data is not a valid JSON object ('.$response->getBody().')' );
		$message->timeTransfer		= round( ( $time2 - $time1 ) * 1000000, 3 );
		$message->timeComplete		= round( ( $time3 - $time0 ) * 1000000, 3 );
		$message->timestampStart	= $time0;
		$message->timestampEncoded	= $time1;
		$message->timestampReceived	= $time2;
		$message->timestampDecoded	= $time3;
		return $message;
	}

	public function proceed( $method, $arguments = array() ){
		$message	= $this->request( $method, $arguments );
		if( $message->status == 'error' ){
			if( !empty( $message->serial ) ){
//				xmp( $message->serial );
				$exception	= @unserialize( $message->serial );
				if( $exception instanceof __PHP_Incomplete_Class )
					throw new RuntimeException( 'Exception class missing' );
				if( $exception instanceof Exception_Serializable )
					throw unserialize( $message->serial );
			}
			throw new RuntimeException( $message->data );
		}	
		return $message;
	}
}
?>

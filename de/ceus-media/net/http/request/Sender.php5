<?php
/**
 *	Request for HTTP Protocol.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		net.http.request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
import( 'de.ceus-media.net.http.Header' );
import( 'de.ceus-media.net.http.Headers' );
/**
 *	Request for HTTP Protocol.
 *	@category		cmClasses
 *	@package		net.http.request
 *	@uses			Net_HTTP_Header
 *	@uses			Net_HTTP_Headers
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		0.6
 */
class Net_HTTP_Request_Sender
{
	/**	@var		string		$host			Host IP to be connected to */
	protected $host;
	/**	@var		string		$uri			URI to request to */
	protected $uri;
	/**	@var		string		$port			Service Port of Host */
	protected $port;
	/**	@var		string		$method			Method of Request (GET or POST) */
	protected $method;
	/**	@var		Net_HTTP_Headers	$headers	Object of collected HTTP Headers */
	protected $headers			= NULL;
	/**	@var		string		$contentType	Default Content Type of Request */
	protected $contentType		= "application/x-www-form-urlencoded";
	
	protected $version			= "1.0";

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$host			Host adresse (IP or Hostname)
	 *	@param		string		$uri			URI of Request
	 *	@param		int			$port			Port of Request
	 *	@param		string		$method			Method of Request (GET or POST)
	 *	@return		void
	 */
	public function __construct( $host, $uri, $port = 80, $method = "POST" )
	{
		$this->host 	= $host;
		$this->uri  	= $uri;
		$this->port 	= $port;
		$this->method	= strtoupper( $method );
		$this->headers	= new Net_HTTP_Headers;
	}

	public function addHeader( Net_HTTP_Header $header )
	{
		$this->headers->addHeader( $header );
	}
	
	public function addHeaderPair( $name, $value )
	{
		$this->headers->addHeaderPair( $name, $value );
	}

	public function setContentType( $mimeType )
	{
		$this->contentType	= $mimeType;
	}
	
	public function setVersion( $version )
	{
		if( !preg_match( '/^[0-9](\.[0-9])?$/', $version ) )
			throw new InvalidArgumentException( 'Invalid HTTP version "'.$version.'"' );
		$this->version	= $version;
	}

	/**
	 *	Sends data via prepared Request.
	 *	@access		public
	 *	@param		array		$headers		Array of HTTP Headers
	 *	@param		string		$data			Data to be sent
	 *	@return		bool
	 */
	public function send( $headers, $data = "" )
	{
		if( is_array( $data ) )
			$data	= http_build_query( $data );
		else if( !is_string( $data ) )
			throw new InvalidArgumentException( 'Data must be String or Array' );
	
		$this->addHeaderPair( 'Host', $this->host );
		$this->addHeaderPair( 'Content-Type', $this->contentType );
		if( getEnv( "SERVER_ADDR" ) )
			$this->addHeaderPair( 'Referer', getEnv( "SERVER_ADDR" ) );

		if( $data )
			$this->addHeaderPair( "Content-Length", strlen( $data ) );
		$headers[] = new Net_HTTP_Request_Header( "Connection", "close\r\n" );

		$result	= "";
		$fp = fsockopen( $this->host, $this->port );
		if( $fp )
		{
			fputs( $fp, $this->method." ".$this->uri." HTTP/".$this->version."\r\n" );				//  send Request
			foreach( $this->headers->getHeaders() as $header )										//  iterate Headers
				fputs( $fp, $header->toString()."\r\n" );											//  send Header
			fputs( $fp, "\r\n" );																	//  close Headers
			fputs( $fp, $data );																	//  send Data
			while( !feof( $fp ) )																	//  receive Response
				$result .= fgets( $fp, 128 );														//  collect Response chunks
			fclose( $fp );																			//  close Connection
			return $result;																			//  return Response String
		}
	}
}
?>
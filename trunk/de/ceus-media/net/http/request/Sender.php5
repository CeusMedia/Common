<?php
import( 'de.ceus-media.net.http.request.Header' );
/**
 *	Request for HTTP Protocol.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
/**
 *	Request for HTTP Protocol.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Request_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.6
 */
class Net_HTTP_Request_Sender
{
	/**	@var	string	$host			Host IP to be connected to */
	protected $host;
	/**	@var	string	$uri			URI to request to */
	protected $uri;
	/**	@var	string	$port			Service Port of Host */
	protected $port;
	/**	@var	string	$method			Method of Request (GET or POST) */
	protected $method;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$host		Host adresse (IP or Hostname)
	 *	@param		string	$uri		URI of Request
	 *	@param		int		$port		Port of Request
	 *	@param		string	$method		Method of Request (GET or POST)
	 *	@return		void
	 */
	public function __construct( $host, $uri, $port = 80, $method = "POST" )
	{
		$this->host 	= $host;
		$this->uri  	= $uri;
		$this->port 	= $port;
		$this->method	= strtoupper( $method );
	}

	/**
	 *	Sends data via prepared Request.
	 *	@access		public
	 *	@param		array	$headers		Array of HTTP Headers
	 *	@param		string	$data		Data to be sent
	 *	@return		bool
	 */
	public function send( $headers, $data )
	{
		$headers[] = new Net_HTTP_Request_Header( "Host", $this->host );
		$headers[] = new Net_HTTP_Request_Header( "Referer", getEnv( "SERVER_ADDR" ) );
		$headers[] = new Net_HTTP_Request_Header( "Content-type", "application/x-www-form-urlencoded" );
		$headers[] = new Net_HTTP_Request_Header( "Content-length", strlen( $data ) );
		$headers[] = new Net_HTTP_Request_Header( "Connection", "close\r\n" );

		$result	= "";
		$fp = fsockopen( $this->host, $this->port );
		if( $fp )
		{
			fputs( $fp, $this->method." ".$this->uri." HTTP/1.1\r\n" );
			foreach( $headers as $header )
				fputs( $fp, $header->toString() );
			fputs( $fp, $data );
			while( !feof( $fp ) )
				$result .= fgets( $fp, 128 );
			fclose( $fp );
			return $result;
		}
	}
}
?>
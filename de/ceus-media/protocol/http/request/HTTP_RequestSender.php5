<?php
import( 'de.ceus-media.protocol.http.request.HTTP_Header' );
import( 'de.ceus-media.protocol.Request' );
/**
 *	Request for HTTP Protocol v1.1.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Request
 *	@uses			HTTP_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
/**
 *	Request for HTTP Protocol.
 *	@package		protocol
 *	@subpackage		http
 *	@extends		Request
 *	@uses			HTTP_Header
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@version		0.1
 */
 class HTTP_RequestSender extends Request
{
	/**	@var	string	$_method		Method of Request (GET or POST) */
	var $_method;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string	$host		Host adresse (IP or Hostname)
	 *	@param		string	$uri			URI of Request
	 *	@param		int		$port		Port of Request
	 *	@param		string	$method		Method of Request (GET or POST)
	 *	@return		void
	 */
	public function __construct( $host, $uri, $port = 80, $method = "POST" )
	{
		parent::__construct( $host, $uri, $port );
		$this->_method = strtoupper( $method );
	}

	/**
	 *	Sends data via prepared Request.
	 *	@access		public
	 *	@param		array	$headers		Array of HTTP Headers
	 *	@param		string	$data		Data to be sent
	 *	@return		bool
	 */
	function send( $headers, $data )
	{
		$headers[] = new HTTP_Header( "Host", $this->_host );
		$headers[] = new HTTP_Header( "Referer", getEnv( "SERVER_ADDR" ) );
		$headers[] = new HTTP_Header( "Content-type", "application/x-www-form-urlencoded" );
		$headers[] = new HTTP_Header( "Content-length", strlen( $data ) );
		$headers[] = new HTTP_Header( "Connection", "close\r\n" );

		$result	= "";
		$fp = fsockopen( $this->_host, $this->_port );
		if( $fp )
		{
			fputs( $fp, $this->_method." ".$this->_uri." HTTP/1.1\r\n" );
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
<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Compression
 *	@uses			Net_HTTP_EncodingSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@package		net.http.request
 *	@uses			Net_HTTP_Compression
 *	@uses			Net_HTTP_EncodingSniffer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
class Net_HTTP_Request_Response
{
	/** @var		string		$status			Status of Response */
	private $status				= "200 OK";
	/** @var		array		$headers		Array of Headers */
	private $headers			= array();
	/** @var		string		$body			Body of Response */
	private $body				= "";

	/**
	 *	Sets a Header.
	 *	@access		public
	 *	@param		string		$name		Name of Response Header
	 *	@param		mixed		$value 		Value of Response Header
	 *	@return		void
	 */
	public function addHeader( $name, $value )
	{
		if( !isset( $this->headers[$name] ) )
			$this->headers[$name]	= array();
		$this->headers[$name][]	= $value;
	}

	/**
	 *	Sends Contents and returns Length of sent Response Content.
	 *	@access		public
	 *	@param		string		$compression	Compression Method (deflate|gzip)
	 *	@return		int
	 */
	public static function sendContent( $content, $compression = NULL )
	{
		$response	= new Net_HTTP_Request_Response();
		$response->write( $content );
		return $response->send( $compression );
	}
	
	/**
	 *	Sends complete Response and returns Length of sent Response Content.
	 *	@access		public
	 *	@param		string		$compressionMethod	Compression Method (deflate|gzip)
	 *	@param		string		$compressionLogFile	File Name of Compression Log
	 *	@return		int
	 */
	public function send( $useCompression = FALSE, $compressionLogFile = NULL )
	{
		header( "HTTP/1.1 ".$this->status );
		foreach( $this->headers as $name => $headers )
			foreach( $headers as $header )
				header( $name.": ".$header );
		if( $useCompression )
		{
			import( 'de.ceus-media.net.http.Compression' );			
			import( 'de.ceus-media.net.http.EncodingSniffer' );			
			$compressionMethods	= Net_HTTP_Compression::getMethods();
			$compressionMethod	= Net_HTTP_Compression::getMethod();
			$compressionMethod	= Net_HTTP_EncodingSniffer::getEncoding( $compressionMethods, $compressionMethod );
			Net_HTTP_Compression::setMethod( $compressionMethod, $compressionLogFile );
			$length	= Net_HTTP_Compression::sendContent( $this->body );
			$this->headers	= array();
			$this->body		= "";
			return $length;
		}
		ob_flush();
		$length	= strlen( $this->body );
#		header( "Content-Length: ".$length );
		print( $this->body );
		flush();
		$this->headers	= array();
		$this->body		= "";
		return $length;
	}
	
	/**
	 *	Sets Status of Response.
	 *	@access		public
	 *	@param		string		$status		Status to be set
	 *	@return		void
	 */
	public function setStatus( $status )
	{
		$this->status	= $status;
	}
	
	/**
	 *	Writes Data to Response.
	 *	@access		public
	 *	@param		string		$data		Data to be responsed
	 *	@return		void
	 */
	public function write( $data )
	{
		$this->body	.= $data;
	}
	
}
?>
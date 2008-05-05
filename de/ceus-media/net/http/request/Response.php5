<?php
/**
 *	Handler for HTTP Responses.
 *	@package		net.http.request
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.6
 */
/**
 *	Handler for HTTP Responses.
 *	@package		net.http.request
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
		$this->headers[$name]	= $value;
	}
	
	/**
	 *	Sends complete Response and returns Length of Response Content.
	 *	@access		public
	 *	@return		int
	 */
	public function send()
	{
		header( "HTTP/1.1 ".$this->status );
		foreach( $this->headers as $name => $value )
			header( $name.": ".$value );
		$length	= strlen( $this->body );
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
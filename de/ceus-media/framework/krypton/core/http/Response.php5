<?php
import( 'de.ceus-media.framework.krypton.interface.core.Response' );
/**
 *	Handler for HTTP Responses.
 *	@package		mv2.core.http
 *	@implements		Framework_Krypton_Interface_Core_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.2
 */
/**
 *	Handler for HTTP Responses.
 *	@package		mv2.core.http
 *	@implements		Framework_Krypton_Interface_Core_Response
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			20.02.2007
 *	@version		0.2
 *	@deprecated		use Net_HTTP_Request instead
 *	@todo			to be deleted
 */
class Framework_Krypton_Core_HTTP_Response implements Framework_Krypton_Interface_Core_Response
{
	/** @var	string		$status			Status of Response */
	private $status		= "200 OK";
	/** @var	array		$headers		Array of Headers */
	private $headers	= array();
	/** @var	string		$body			Body of Response */
	private $body		= null;

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
	 *	Sends complete Response
	 *	@access		public
	 *	@return		void
	 */
	public function flush()
	{
		header( "HTTP/1.0 ".$this->status );
		foreach( $this->headers as $name => $value )
		{
			header( $name.": ".$value );
		}
		print $this->body;
		$this->headers	= array();
		$this->body		= null;
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
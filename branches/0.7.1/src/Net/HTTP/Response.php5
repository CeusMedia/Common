<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *
 *	Copyright (c) 2007-2010 Christian Würker (ceus-media.de)
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
 *	@package		Net.HTTP
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id: Response.php5 666 2010-05-17 22:34:19Z christian.wuerker $
 */
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@uses			Net_HTTP_Compression
 *	@uses			Net_HTTP_Sniffer_Encoding
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id: Response.php5 666 2010-05-17 22:34:19Z christian.wuerker $
 */
class Net_HTTP_Response
{
	protected $body			= NULL;
	public $headers			= NULL;
	protected $protocol		= 'HTTP';
	protected $status		= '200 OK';
	protected $version		= '1.0';
	protected $length		= 0;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$protocol		Response protocol
	 *	@param		string		$version		Response protocol version
	 *	@return		void
	 */
	public function __construct( $protocol = NULL, $version = NULL )
	{
		$this->headers	= new Net_HTTP_Headers();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
		$this->body		= NULL;
	}

	/**
	 *	Adds an HTTP header object.
	 *	@access		public
	 *	@param		Net_HTTP_Header	$header		HTTP header object
	 *	@return		void
	 */
	public function addHeader( Net_HTTP_Header $header )
	{
		$this->headers->addHeader( $header );
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string			$name		HTTP header name
	 *	@param		string			$value		HTTP header value
	 *	@return		void
	 */
	public function addHeaderPair( $name, $value )
	{
		$this->headers->addHeaderPair( $name, $value );
	}

	/**
	 *	Returns response message body.
	 *	@access		public
	 *	@return		string			Response message body
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@param		string			Header name
	 *	@return		array			List of Header values
	 */
	public function getHeader( $key )
	{
		return $this->headers->getHeadersByName( $key );
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@return		array			List of response HTTP headers
	 */
	public function getHeaders()
	{
		return $this->headers->getHeaders();
	}

	public function getLength()
	{
		return $this->length;
	}

	/**
	 *	Returns response protocol.
	 *	@access		public
	 *	@return		string			Response protocol
	 */
	public function getProtocol()
	{
		return $this->protocol;
	}

	/**
	 *	Returns response status code.
	 *	@access		public
	 *	@return		string			Response HTTP status code
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 *	Returns response protocol version.
	 *	@access		public
	 *	@return		string			Response protocol version
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Indicates whether an HTTP header is set.
	 *	@access		public
	 *	@param		string			$key				Header name
	 *	@return		bool
	 */
	public function hasHeader( $key )
	{
		return $this->headers->hasHeader( $key );
	}

	/**
	 *	Sets response message body.
	 *	@access		public
	 *	@param		string		$body			Response message body
	 *	@return		void
	 */
	public function setBody( $body )
	{
		if( !is_string( $body ) )
			throw new InvalidArgumentException( 'Must be string' );
		$this->body	= $body;
	}

	/**
	 *	Sets response protocol.
	 *	@access		public
	 *	@param		string		$protocol		Response protocol
	 *	@return		void
	 */
	public function setProtocol( $protocol )
	{
		$this->protocol	= $protocol;
	}

	/**
	 *	Sets response protocol.
	 *	@access		public
	 *	@param		string		$status			Response status code
	 *	@return		void
	 *	@see		http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 *	@see		http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 */
	public function setStatus( $status )
	{
		$this->status	= $status;
	}

	/**
	 *	Sets response protocol version.
	 *	@access		public
	 *	@param		string		$version		Response protocol version
	 *	@return		void
	 */
	public function setVersion( $version )
	{
		$this->version	= $version;
	}

	/**
	 *	Renders complete request string.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		$lines	= array();
		$lines[]	= $this->protocol.'/'.$this->version.' '.$this->status;
		$lines[]	= $this->headers->toString();
		$lines[]	= '';
		if( $this->body )
			$lines[]	= $this->body;
		return join( "\r\n", $lines );
	}
}
?>
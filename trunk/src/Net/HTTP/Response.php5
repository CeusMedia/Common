<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id$
 */
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@uses			Net_HTTP_Header_Section
 *	@uses			Net_HTTP_Header_Field
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2013 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id$
 */
class Net_HTTP_Response
{
	protected $body			= NULL;
	public $headers			= NULL;
	protected $protocol		= 'HTTP';
	protected $status		= '200 OK';
	protected $version		= '1.0';

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$protocol		Response protocol
	 *	@param		string		$version		Response protocol version
	 *	@return		void
	 */
	public function __construct( $protocol = NULL, $version = NULL )
	{
		$this->headers	= new Net_HTTP_Header_Section();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
		$this->body		= NULL;
	}

	/**
	 *	Adds an HTTP header field object.
	 *	@access		public
	 *	@param		Net_HTTP_Header_Field	$field		HTTP header field object
	 *	@return		void
	 */
	public function addHeader( Net_HTTP_Header_Field $field, $emptyBefore = NULL )
	{
		$this->headers->setField( $field, $emptyBefore );
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string		$name		HTTP header name
	 *	@param		string		$value		HTTP header value
	 *	@return		void
	 */
	public function addHeaderPair( $name, $value, $emptyBefore = NULL )
	{
		$this->headers->setField( new Net_HTTP_Header_Field( $name, $value ), $emptyBefore );
	}

	/**
	 *	Returns response message body.
	 *	@access		public
	 *	@return		string		Response message body
	 */
	public function getBody()
	{
		return $this->body;
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@param		string		$string			Header name
	 *	@param		bool		$first			Flag: return first header only
	 *	@return		array|Net_HTTP_Header_Field		List of header fields or only one header field if requested so
	 */
	public function getHeader( $key, $first = NULL )
	{
		$fields	= $this->headers->getFieldsByName( $key );											//  get all header fields with this header name
		if( !$first )																				//  all header fields shall be returned
			return $fields;																			//  return all header fields
		if( $fields )																				//  otherwise: header fields (atleat one) are set
			return $fields[0];																		//  return first header field
		return new Net_HTTP_Header_Field( $key, NULL );												//  otherwise: return empty fake header field
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@return		array		List of response HTTP header fields
	 */
	public function getHeaders()
	{
		return $this->headers->getFields();
	}

	/**
	 *	Returns response protocol.
	 *	@access		public
	 *	@return		string		Response protocol
	 */
	public function getProtocol()
	{
		return $this->protocol;
	}

	/**
	 *	Returns response status code.
	 *	@access		public
	 *	@return		string		Response HTTP status code
	 */
	public function getStatus()
	{
		return $this->status;
	}

	/**
	 *	Returns response protocol version.
	 *	@access		public
	 *	@return		string		Response protocol version
	 */
	public function getVersion()
	{
		return $this->version;
	}

	/**
	 *	Indicates whether an HTTP header is set.
	 *	@access		public
	 *	@param		string		$key			Header name
	 *	@return		bool
	 */
	public function hasHeader( $key )
	{
		return $this->headers->hasField( $key );
	}

	public function send( $compression = NULL, $sendLengthHeader = TRUE, $exit = TRUE ){
		$sender	= new Net_HTTP_Response_Sender( $this );
		return $sender->send( $compression, $sendLengthHeader, $exit );
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
			throw new InvalidArgumentException( 'Body must be string' );
		$this->body		= trim( $body );
		$this->headers->setFieldPair( "Content-Length", strlen( $this->body ), TRUE );
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
	 *	Renders complete response string.
	 *	@access		public
	 *	@return		string
	 */
	public function toString()
	{
		$lines	= array();
		$lines[]	= $this->protocol.'/'.$this->version.' '.$this->status;							//  add main protocol header
		$lines[]	= $this->headers->toString();													//  add header fields and line break
		if( strlen( $this->body ) )																	//  response body is set
			$lines[]	= $this->body;																//  add response body
		return join( "\r\n", $lines );																//  glue parts with line break and return result
	}
}
?>
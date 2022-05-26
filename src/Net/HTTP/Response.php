<?php
/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2007
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;
use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use InvalidArgumentException;

/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2007
 */
class Response
{
	public $headers			= NULL;

	protected $body			= NULL;
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
		$this->headers	= new HeaderSection();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
		$this->body		= NULL;
	}

	/**
	 *	Adds an HTTP header field object.
	 *	@access		public
	 *	@param		HeaderField		$field			HTTP header field object
	 *	@param		boolean				$emptyBefore	Flag: clear beforehand set headers with this name (default: no)
	 *	@return		void
	 */
	public function addHeader( HeaderField $field, $emptyBefore = FALSE )
	{
		$this->headers->setField( $field, $emptyBefore );
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string		$name			HTTP header name
	 *	@param		string		$value			HTTP header value
	 *	@param		boolean		$emptyBefore	Flag: clear beforehand set headers with this name (default: no)
	 *	@return		void
	 */
	public function addHeaderPair( $name, $value, $emptyBefore = FALSE )
	{
		$this->headers->setField( new HeaderField( $name, $value ), $emptyBefore );
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
	 *	@return		array|HeaderField		List of header fields or only one header field if requested so
	 */
	public function getHeader( $key, $first = NULL )
	{
		//  get all header fields with this header name
		$fields	= $this->headers->getFieldsByName( $key );
		//  all header fields shall be returned
		if( !$first )
			//  return all header fields
			return $fields;
		//  otherwise: header fields (atleat one) are set
		if( $fields )
			//  return first header field
			return $fields[0];
		//  otherwise: return empty fake header field
		return new HeaderField( $key, NULL );
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
	 *	Returns length of current response.
	 *	@access		public
	 *	@return		integer		Byte length of current response
	 */
	public function getLength()
	{
		return strlen( $this->toString() );
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
		$sender	= new Response\Sender( $this );
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
	 *	Sets response HTTP header, overriding before set values.
	 *	@access		public
	 *	@param		string		$name		HTTP header name
	 *	@param		string		$value		HTTP header value
	 *	@return		void
	 */
	public function setHeader( $key, $value ){
		$this->addHeaderPair( $key, $value, TRUE );
	}

	/**
	 *	Sets response protocol. Set initially to HTTP.
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
	 *	You can set a pure (integer) status code (e.G. 200) and the status message (e.G. OK) will be added automatically.
	 *	You can also set the complete HTTP status containing code and message (e.G. "200 OK").
	 *	@access		public
	 *	@param		int|string		$status			Response status code (as integer) or status code with message (e.G. 404 Not Found)
	 *	@param		boolean			$strict			Flag: ignore given status message and resolve using Net_HTTP_Status
	 *	@return		void
	 *	@see		http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 *	@see		http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 */
	public function setStatus( $status, $strict = FALSE )
	{
		//  strict mode: always resolve status message
		$status	= $strict ? (int) $status : $status;
		//  only status code given
		if( is_int( $status ) || !preg_match( "/[a-z]/i", $status ) )
			//  extend status code by status message
			$status	= ( (int) $status ).' '.Status::getText( (int) $status );
		//  store status code and message
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
		//  add main protocol header
		$lines[]	= $this->protocol.'/'.$this->version.' '.$this->status;
		//  add header fields and line break
		$lines[]	= $this->headers->toString();
		//  response body is set
		if( strlen( $this->body ) )
			//  add response body
			$lines[]	= $this->body;
		//  glue parts with line break and return result
		return join( "\r\n", $lines );
	}
}

<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *
 *	Copyright (c) 2007-2024 Christian Würker (ceusmedia.de)
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
 *	along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;
use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use CeusMedia\Common\Net\HTTP\Response\Sender as ResponseSender;

/**
 *	Handler for HTTP Responses with HTTP Compression Support.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class Response
{
	public HeaderSection $headers;

	protected ?string $body			= NULL;
	protected string $protocol		= 'HTTP';
	protected string $status		= '200 OK';
	protected string $version		= '1.0';
	protected ?Request $request		= NULL;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string|NULL		$protocol		Response protocol
	 *	@param		string|NULL		$version		Response protocol version
	 *	@return		void
	 */
	public function __construct( ?string $protocol = NULL, ?string $version = NULL )
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
	 *	@return		static
	 */
	public function addHeader( HeaderField $field, bool $emptyBefore = FALSE ): static
	{
		$this->headers->setField( $field, $emptyBefore );
		return $this;
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string				$name			HTTP header name
	 *	@param		string|int|float	$value			HTTP header value
	 *	@param		boolean				$emptyBefore	Flag: clear beforehand set headers with this name (default: no)
	 *	@return		static
	 */
	public function addHeaderPair( string $name, string|int|float $value, bool $emptyBefore = FALSE ): static
	{
		$this->headers->setField( new HeaderField( $name, $value ), $emptyBefore );
		return $this;
	}

	/**
	 *	Returns response message body.
	 *	@access		public
	 *	@return		?string		Response message body
	 */
	public function getBody(): ?string
	{
		return $this->body;
	}

	/**
	 *	Returns length of body or 0.
	 *	This method exists to use mbstring (multibyte string) support
	 *	@return		int
	 */
	public function getBodyLength(): int
	{
		if( NULL === $this->body )
			return 0;
		if( function_exists( 'mb_strlen' ) )
			return mb_strlen( $this->body );
		return strlen( $this->body );
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@param		string		$key			Header name
	 *	@param		bool		$first			Flag: return first header only
	 *	@return		array|HeaderField		List of header fields or only one header field if requested so
	 */
	public function getHeader( string $key, bool $first = NULL ): array|HeaderField
	{
		//  get all header fields with this header name
		$fields	= $this->headers->getFieldsByName( $key );
		//  all header fields shall be returned
		if( !$first )
			//  return all header fields
			return $fields;
		//  otherwise: header fields (at least one) are set
		if( $fields )
			//  return first header field
			return $fields[0];
		//  otherwise: return empty fake header field
		return new HeaderField( $key, '' );
	}

	/**
	 *	Returns response headers.
	 *	@access		public
	 *	@return		array		List of response HTTP header fields
	 */
	public function getHeaders(): array
	{
		return $this->headers->getFields();
	}

	/**
	 *	Returns length of current response.
	 *	@access		public
	 *	@return		integer		Byte length of current response
	 */
	public function getLength(): int
	{
		return strlen( $this->toString() );
	}

	/**
	 *	Returns response protocol.
	 *	@access		public
	 *	@return		string		Response protocol
	 */
	public function getProtocol(): string
	{
		return $this->protocol;
	}

	/**
	 *	Returns response status code.
	 *	@access		public
	 *	@return		string		Response HTTP status code
	 */
	public function getStatus(): string
	{
		return $this->status;
	}

	/**
	 *	Returns response protocol version.
	 *	@access		public
	 *	@return		string		Response protocol version
	 */
	public function getVersion(): string
	{
		return $this->version;
	}

	/**
	 *	Indicates whether an HTTP header is set.
	 *	@access		public
	 *	@param		string		$key			Header name
	 *	@return		bool
	 */
	public function hasHeader( string $key ): bool
	{
		return $this->headers->hasField( $key );
	}

	/**
	 *	@param		string|NULL		$compression
	 *	@param		boolean			$sendLengthHeader	Flag: Send Content-Length Header (default: yes)
	 *	@param		boolean			$andExit			Flag: after afterwards (default: no)
	 *	@return		Response
	 */
	public function send( ?string $compression = NULL, bool $sendLengthHeader = TRUE, bool $andExit = TRUE ): Response
	{
		$sender	= new ResponseSender( $this, $this->request );
		$sender->setCompression( $compression );
		return $sender->send( $sendLengthHeader, $andExit );
	}

	/**
	 *	Sets response message body.
	 *	@access		public
	 *	@param		string		$body			Response message body
	 *	@return		static
	 */
	public function setBody( string $body ): static
	{
		$this->body		= $body;
		$this->headers->setFieldPair( 'Content-Length', $this->getBodyLength() );
		return $this;
	}

	/**
	 *	Sets response HTTP header, overriding before set values.
	 *	@access		public
	 *	@param		string				$key		HTTP header name
	 *	@param		string|int|float	$value		HTTP header value
	 *	@return		static
	 */
	public function setHeader( string $key, string|int|float $value ): static
	{
		$this->addHeaderPair( $key, $value, TRUE );
		return $this;
	}

	/**
	 *	Sets response protocol. Set initially to HTTP.
	 *	@access		public
	 *	@param		string		$protocol		Response protocol
	 *	@return		static
	 */
	public function setProtocol( string $protocol ): static
	{
		$this->protocol	= $protocol;
		return $this;
	}

	/**
	 *	Sets response protocol. Set initially to HTTP.
	 *	@access		public
	 *	@param		Request		$request		Request Object
	 *	@return		static
	 */
	public function setRequest( Request $request ): static
	{
		$this->request	= $request;
		return $this;
	}

	/**
	 *	Sets response protocol.
	 *	You can set a pure (integer) status code (e.G. 200) and the status message (e.G. OK) will be added automatically.
	 *	You can also set the complete HTTP status containing code and message (e.G. "200 OK").
	 *	@access		public
	 *	@param		int|string		$status			Response status code (as integer) or status code with message (e.G. 404 Not Found)
	 *	@param		boolean			$strict			Flag: ignore given status message and resolve using Net_HTTP_Status
	 *	@return		static
	 *	@see		https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html
	 *	@see		http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	 */
	public function setStatus( int|string $status, bool $strict = FALSE ): static
	{
		//  strict mode: always resolve status message
		$status	= $strict ? (int) $status : $status;
		//  only status code given
		if( is_int( $status ) || !preg_match( "/[a-z]/i", $status ) )
			//  extend status code by status message
			$status	= ( (int) $status ).' '.Status::getText( (int) $status );
		//  store status code and message
		$this->status	= $status;
		return $this;
	}

	/**
	 *	Sets response protocol version.
	 *	@access		public
	 *	@param		string		$version		Response protocol version
	 *	@return		static
	 */
	public function setVersion( string $version ): static
	{
		$this->version	= $version;
		return $this;
	}

	/**
	 *	Renders complete response string.
	 *	@access		public
	 *	@return		string
	 */
	public function toString(): string
	{
		$lines		= [];
		//  add main protocol header
		$lines[]	= $this->protocol.'/'.$this->version.' '.$this->status;
		//  add header fields and line break
		$lines[]	= $this->headers->render();
		//  response body is set
		if( 0 !== $this->getBodyLength() )
			//  add response body
			$lines[]	= $this->body;
		//  glue parts with line break and return result
		return join( "\r\n", $lines );
	}
}

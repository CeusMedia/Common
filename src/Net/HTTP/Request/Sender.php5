<?php
/**
 *	Request for HTTP Protocol.
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
 *	@package		Net.HTTP.Request
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 */
/**
 *	Request for HTTP Protocol.
 *	@category		cmClasses
 *	@package		Net.HTTP.Request
 *	@uses			Net_HTTP_Header_Field
 *	@uses			Net_HTTP_Header_Section
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@version		$Id$
 *	@todo			fix: 400 Bad Request
 */
class Net_HTTP_Request_Sender
{
	/**	@var	string					$host			Host IP to be connected to */
	protected $host;
	/**	@var	string					$uri			URI to request to */
	protected $uri;
	/**	@var	string					$port			Service Port of Host */
	protected $port						= -1;
	/**	@var	string					$method			Method of Request (GET or POST) */
	protected $method;
	/**	@var	Net_HTTP_Header_Section	$headers		Object of collected HTTP Headers */
	protected $headers					= NULL;
	/**	@var	string					$version		HTTP version (1.0 or 1.1) */
	protected $version					= '1.1';
	/**	@var	string					$data			Raw POST data */
	protected $data;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$host			Host adresse (IP or Hostname)
	 *	@param		string		$uri			URI of Request
	 *	@param		int			$port			Port of Request
	 *	@param		string		$method			Method of Request (GET or POST)
	 *	@return		void
	 */
	public function __construct( $host, $uri, $port = 80, $method = 'GET' )
	{
		$this->headers	= new Net_HTTP_Header_Section;
		$this->headers->addFieldPair( 'Host', $host );
		$this->host		= $host;
		$this->setUri( $uri );
		$this->setPort( $port );
		$this->setMethod( $method );
	}

	public function addHeader( Net_HTTP_Header_Field $field )
	{
		$this->headers->addField( $field );
	}
	
	public function addHeaderPair( $name, $value )
	{
		$this->headers->addField( new Net_HTTP_Header_Field( $name, $value ) );
	}

/*	public function setContentType( $mimeType )
	{
		$this->contentType	= $mimeType;
	}
*/
	public function setData( $data )
	{
		if( !is_array( $data ) )
			throw new InvalidArgumentException( 'Must be an array' );
		$this->data	= http_build_query( $data, NULL, '&' );
	}

	public function setMethod( $method )
	{
		$method	= strtoupper( $method );
		if( !in_array( $method, array( 'GET', 'POST', 'DELETE', 'PUT', 'HEAD' ) ) )
			throw new InvalidArgumentException( 'Invalid HTTP method "'.$method.'"' );
		$this->method	= $method;
	}

	public function setPort( $port )
	{
		$this->port	= $port;
	}

	public function setRawData( $data )
	{
		if( !is_string( $data ) )
			throw new InvalidArgumentException( 'Must be a string' );
		$this->data	= $data;
	}

	public function setUri( $uri )
	{
		if( !is_string( $uri ) )
			throw new InvalidArgumentException( 'Must be a string' );
		if( !trim( $uri ) )
			throw new InvalidArgumentException( 'Must be a URI' );
		$this->uri	= $uri;
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
	 *	@return		Net_HTTP_Response
	 */
	public function send()
	{
		if( trim( $this->data ) )
			$this->addHeaderPair( "Content-Length", strlen( $this->data ) );
		if( !$this->headers->getFieldsByName( 'connection' ) )
			$this->addHeaderPair( 'Connection', 'close' );

		$result	= "";
		$fp = fsockopen( $this->host, $this->port, $errno, $errstr, 2 );
		if( !$fp )
			throw new RuntimeException( $errstr.' ('.$errno.')' );

		$lines	= array(
			$this->method." ".$this->uri." HTTP/".$this->version,
			$this->headers->toString(),
			''
		);
		fwrite( $fp, join( "\r\n", $lines ) );																		//  send Request
		if( trim( $this->data ) )
			fwrite( $fp, $this->data );																//  send Request
		while( !feof( $fp ) )																		//  receive Response
			$result .= fgets( $fp, 128 );															//  collect Response chunks
		fclose( $fp );																				//  close Connection
		return Net_HTTP_Response_Parser::fromString( $result );
	}
}
?>
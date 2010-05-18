<?php
/**
 *	Handler for HTTP Requests.
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
 *	Handler for HTTP Requests.
 *	@category		cmClasses
 *	@package		Net.HTTP
 *	@extends		ADT_List_Dictionary
 *	@uses			Net_HTTP_Header
 *	@uses			Net_HTTP_Headers
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2010 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			20.02.2007
 *	@version		$Id: Response.php5 666 2010-05-17 22:34:19Z christian.wuerker $
 */
class Net_HTTP_Request extends ADT_List_Dictionary
{
	protected $body;
	public $headers;
	protected $protocol		= 'HTTP';
	protected $status		= '200 OK';
	protected $version		= '1.0';

	public function __construct( $protocol = NULL, $version = NULL )
	{
		$this->headers	= new Net_HTTP_Headers();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
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
		$this->headers->addHeader( new Net_HTTP_Header( $name, $value ) );
	}

	public function fromEnv( $useSession = FALSE, $useCookie = FALSE )
	{
		$sources	= array(
			"get"	=> &$_GET,
			"post"	=> &$_POST,
			"files"	=> &$_FILES,
		);
		if( $useSession )
			$sources['session']	=& $_SESSION;
		if( $useCookie )
			$sources['cookie']	=& $_COOKIE;

#		$this->ip	= getEnv( 'REMOTE_ADDR' );
		foreach( $sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );

		/*  --  RETRIEVE HTTP HEADERS  --  */
		foreach( $_SERVER as $key => $value )
		{
			if( strpos( $key, "HTTP_" ) !== 0 )
				continue;
			$key	= preg_replace( '/^HTTP_/', '', $key );											//  strip HTTP prefix
			$key	= preg_replace( '/_/', '-', $key );												//  replace underscore by dash
			$this->headers->addHeader( new Net_HTTP_Header( $key, $value ) );						//
		}
		$this->body	= file_get_contents( "php://input" );
	}

	public function fromString( $request )
	{
		throw new Exception( 'Not implemented' );
	}

	public function getHeaders()
	{
		return $this->headers->getHeaders();
	}

	public function getHeadersByName( $name )
	{
		return $this->headers->getHeadersByName( $name );
	}

	public function isAjax()
	{
		return $this->headers->hasHeader( 'X-Requested-With' );
	}

	public function remove( $key )
	{
		remark( 'R:remove: '.$key );
		parent::remove( $key );
		$this->body	= http_build_query( $this->getAll(), NULL, '&' );
	}

	public function set( $key, $value )
	{
		parent::set( $key, $value );
		$this->body	= http_build_query( $this->getAll(), NULL, '&' );
	}

	public function setAjax( $value = 'X-Requested-With' )
	{
		$this->headers->addHeader( new Net_HTTP_Header( 'X-Requested-With', $value ) );
	}
}
?>
<?php
/**
 *	Handler for HTTP Requests.
 *
 *	Copyright (c) 2007-2015 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2007
 *	@version		$Id$
 */
/**
 *	Handler for HTTP Requests.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@extends		ADT_List_Dictionary
 *	@uses			Net_HTTP_Header_Field
 *	@uses			Net_HTTP_Header_Section
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2015 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@since			20.02.2007
 *	@version		$Id$
 *	@todo			Finish implementation: this is bastard of request and reponse
 */
class Net_HTTP_Request extends ADT_List_Dictionary
{
	protected $body;
	/** @var		Net_HTTP_Header_Section	$headers		Object of collected HTTP Headers */
	public $headers;
	/**	@var		string					$ip				IP of Request */
	protected $ip;
	/** @var		string					$method			HTTP request method */
	protected $method		= 'GET';
	protected $protocol		= 'HTTP';
	protected $status		= '200 OK';
	protected $version		= '1.0';

	public function __construct( $protocol = NULL, $version = NULL )
	{
		$this->headers	= new Net_HTTP_Header_Section();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
	}

	/**
	 *	Adds an HTTP header object.
	 *	@access		public
	 *	@param		Net_HTTP_Header_Field	$header		HTTP Header Field Object
	 *	@return		void
	 */
	public function addHeader( Net_HTTP_Header_Field $field )
	{
		$this->headers->addField( $field );
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
		$this->headers->addField( new Net_HTTP_Header_Field( $name, $value ) );
	}

	public function fromEnv( $useSession = FALSE, $useCookie = FALSE )
	{
		$this->sources	= array(
			"get"	=> &$_GET,
			"post"	=> &$_POST,
			"files"	=> &$_FILES,
		);
		if( $useSession )
			$this->sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->sources['cookie']	=& $_COOKIE;

		$this->ip		= getEnv( 'REMOTE_ADDR' );													//  store IP of requesting client
		$this->method	= strtoupper( getEnv( 'REQUEST_METHOD' ) );									//  store HTTP method
		foreach( $this->sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );

#		$this->ip	= getEnv( 'REMOTE_ADDR' );														//  store IP of requesting client
		foreach( $this->sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );

		/*  --  RETRIEVE HTTP HEADERS  --  */
		if( function_exists( 'getallheaders' ) )
		{
			foreach( getallheaders() as $key => $value )
			{
				$this->headers->addField( new Net_HTTP_Header_Field( $key, $value ) );					//  store header
			}
		}
		else
		{
			foreach( $_SERVER as $key => $value )
			{
				if( strpos( $key, "HTTP_" ) !== 0 )
					continue;
				$key	= preg_replace( '/^HTTP_/', '', $key );											//  strip HTTP prefix
				$key	= preg_replace( '/_/', '-', $key );												//  replace underscore by dash
				$this->headers->addField( new Net_HTTP_Header_Field( $key, $value ) );						//  store header
			}
		}
		$this->setMethod( strtoupper( getEnv( 'REQUEST_METHOD' ) ) );								//  store HTTP method
		$this->body	= file_get_contents( "php://input" );											//  store raw POST, PUT or FILE data
	}

	public function fromString( $request )
	{
		throw new Exception( 'Not implemented' );
	}

	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@param		bool		$strict		Flag: throw exception if not set, otherwise return NULL
	 *	@throws		InvalidArgumentException if key is not set in source and strict is on
	 *	@return		array		Pairs in source (or empty array if not set on strict is off)
	 */
	public function getAllFromSource( $source, $strict = FALSE )
	{
		$source	= strtolower( $source );
		if( isset( $this->sources[$source] ) )
			return new ADT_List_Dictionary( $this->sources[$source] );
		if( !$strict )
			return array();
		throw new InvalidArgumentException( 'Invalid source "'.$source.'"' );
	}

	/**
	 *	Returns value or null by its key in a specified source.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@param		bool		$strict		Flag: throw exception if not set, otherwise return NULL
	 *	@throws		InvalidArgumentException if key is not set in source and strict is on
	 *	@return		mixed		Value of key in source or NULL if not set
	 */
	public function getFromSource( $key, $source, $strict = FALSE )
	{
		$data	= $this->getAllFromSource( $source );
		if( isset( $data[$key] ) )
			return $data[$key];
		if( !$strict )
			return NULL;
		throw new InvalidArgumentException( 'Invalid key "'.$key.'" in source "'.$source.'"' );
	}

	/**
	 *	Return HTTP header field by a specified header name.
	 *	Returns latest field if more than one.
	 *	Alias for getHeadersByName with TRUE as seconds parameter.
	 *	But throws an exception if nothing found and strict mode enabled (enabled by default).
	 *	@access		public
	 *	@param		string		$name		Key name of header
	 *	@param		boolean		$strict		Flag: throw exception if nothing found
	 *	@return		Net_HTTP_Header_Field|null
	 *	@throws		RuntimeException		if nothing found and strict mode enabled
	 */
	public function getHeader( $name, $strict = TRUE )
	{
		$header	= $this->getHeadersByName( $name, TRUE );
		if( $header )
			return $header;
		if( !$strict )
			return NULL;
		throw new RuntimeException( sprintf( 'No header set by name "%s"', $name ) );
	}

	/**
	 *	Returns list of all HTTP headers received.
	 *	Acts as getHeadersByName if header key name is given.
	 *	@access		public
	 *	@param		string		$name		Key name of header (optional)
	 *	@return		array		List of Net_HTTP_Header_Field instances
	 */
	public function getHeaders( $name = NULL )
	{
		if( $name )
			return $this->getHeadersByName( $name );
		return $this->headers->getFields();
	}

	/**
	 *	Returns list of HTTP header fields with a specified header name.
	 *	With second parameter only the latest header field will be return, NULL if none.
	 *	@access		public
	 *	@param		string		$name		Key name of header
	 *	@param		boolean		$latestOnly	Flag: return latest header field, only
	 *	@return		array|null	List of HTTP header fields with given header name
	 */
	public function getHeadersByName( $name, $latestOnly = FALSE )
	{
		return $this->headers->getFieldsByName( $name, $latestOnly );
	}

	/**
	 *	Returns received raw POST Data.
	 *	@access		public
	 *	@return		string
	 */
	public function getBody()
	{
		return $this->body;
	}

	public function getMethod()
	{
		return $this->method;
	}

	/**
	 *	Indicates wheter a pair is existing in a request source by its key.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@return		bool
	 */
	public function hasInSource( $key, $source )
	{
		$source	= strtolower( $source );
		return isset( $this->sources[$source][$key] );
	}

	public function isAjax()
	{
		return $this->headers->hasField( 'X-Requested-With' );
	}

	public function remove( $key )
	{
#		remark( 'R:remove: '.$key );
		parent::remove( $key );
//		if( $this->method === "POST" )
//			$this->body	= http_build_query( $this->getAll(), NULL, '&' );
	}

	public function set( $key, $value )
	{
		parent::set( $key, $value );
//		if( $this->method === "POST" )
//			$this->body	= http_build_query( $this->getAll(), NULL, '&' );
	}

	public function setAjax( $value = 'X-Requested-With' )
	{
		$this->headers->addField( new Net_HTTP_Header_Field( 'X-Requested-With', $value ) );
	}

	public function setMethod( $method )
	{
		$this->method	= strtoupper( $method );
	}
}
?>

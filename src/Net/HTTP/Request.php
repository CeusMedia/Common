<?php
/**
 *	Handler for HTTP Requests.
 *
 *	Copyright (c) 2007-2018 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2018 Christian Würker
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
 *	@copyright		2007-2018 Christian Würker
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
	protected $method;
	protected $protocol		= 'HTTP';
	protected $status		= '200 OK';
	protected $version		= '1.0';

	public function __construct( $protocol = NULL, $version = NULL )
	{
		$this->method	= new Net_HTTP_Method();
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
		$this->method->set( getEnv( 'REQUEST_METHOD' ) );												//  store HTTP method
		$this->ip		= getEnv( 'REMOTE_ADDR' );													//  store IP of requesting client
		$this->sources	= array(
			"GET"		=> &$_GET,
			"POST"		=> &$_POST,
			"FILES"		=> &$_FILES,
			"SESSION"	=> array(),
			"COOKIE"	=> array(),
		);
		if( $useSession )
			$this->sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->sources['cookie']	=& $_COOKIE;

		/*  --  APPLY ALL SOURCES TO ONE COLLECTION OF REQUEST ARGUMENT PAIRS  --  */
		foreach( $this->sources as $key => $values )
			$this->pairs	= array_merge( $this->pairs, $values );

		/*  --  RETRIEVE HTTP HEADERS FROM WEBSERVER ENVIRONMENT  --  */
		foreach( self::getAllEnvHeaders() as $key => $value )										//  iterate requested HTTP headers
			$this->headers->addField( new Net_HTTP_Header_Field( $key, $value ) );					//  store header

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
		$source	= strtoupper( $source );
		if( isset( $this->sources[$source] ) )
			return new ADT_List_Dictionary( $this->sources[$source] );
		if( !$strict )
			return array();
		throw new InvalidArgumentException( 'Invalid source "'.$source.'"' );
	}

	static public function getAllEnvHeaders(){
		if( function_exists( 'getallheaders' ) )
			return getallheaders();

		$headers = array();

		$copy_server = array(
			'CONTENT_TYPE'   => 'Content-Type',
			'CONTENT_LENGTH' => 'Content-Length',
			'CONTENT_MD5'    => 'Content-Md5',
		);

		foreach( $_SERVER as $key => $value )
		{
			if( substr( $key, 0, 5 ) === 'HTTP_' )
			{
				$key = substr( $key, 5 );
				if( !(isset( $copy_server[$key] ) && isset( $_SERVER[$key] ) ) )
				{
					$key = str_replace( ' ', '-', ucwords( strtolower( str_replace( '_', ' ', $key ) ) ) );
					$headers[$key] = $value;
				}
			}
			elseif (isset($copy_server[$key]))
			{
				$headers[$copy_server[$key]] = $value;
			}
		}
		if( !isset( $headers['Authorization'] ) )
		{
			if( isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) )
			{
				$headers['Authorization'] = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
			}
			elseif( isset( $_SERVER['PHP_AUTH_USER'] ) )
			{
				$basic_pass = isset( $_SERVER['PHP_AUTH_PW'] ) ? $_SERVER['PHP_AUTH_PW'] : '';
				$headers['Authorization'] = 'Basic ' . base64_encode( $_SERVER['PHP_AUTH_USER'] . ':' . $basic_pass );
			}
			elseif (isset($_SERVER['PHP_AUTH_DIGEST'] ) )
			{
				$headers['Authorization'] = $_SERVER['PHP_AUTH_DIGEST'];
			}
		}
		return $headers;
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
	 *	Returns collection of all HTTP headers received.
	 *	@access		public
	 *	@return		Net_HTTP_Header_Section		Collection of of Net_HTTP_Header_Field instances
	 */
	public function getHeaders()
	{
		return $this->headers;
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

	/**
	 *	Return request method object.
	 *	@access		public
	 *	@return		Net_HTTP_Method
	 */
	public function getMethod()
	{
		return $this->method;
	}

	/**
	 *	Get requested URL, relative of absolute.
	 *	@access		public
	 *	@param		boolean		$absolute		Flag: return absolute URL
	 *	@return		ADT_URL
	 */
	public function getUrl( $absolute = TRUE ){
		$url	= new ADT_URL( getEnv( 'REQUEST_URI' ) );
		if( $absolute ){
			$url->setScheme( getEnv( 'REQUEST_SCHEME' ) );
			$url->setHost( getEnv( 'HTTP_HOST' ) );
		}
		return $url;
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
		$source	= strtoupper( $source );
		return isset( $this->sources[$source][$key] );
	}

	public function isAjax()
	{
		return $this->headers->hasField( 'X-Requested-With' );
	}

	/**
	 *	Indicate whether a specific request method is used.
	 *	Method parameter is not case sensitive.
	 *	@access		public
	 *	@param		string		$method		Request method to check against
	 *	@return		boolean
	 *	@deprecated	use request->getMethod()->is( $method ) instead
	 */
	public function isMethod( $method )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->is( $method ) instead' );
		return $this->method->is( $method );
	}

	/**
	 *	Indicates whether request method is GET.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isGet() instead
	 */
	public function isGet()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isGet() instead' );
		return $this->method()->isGet();
	}

	/**
	 *	Indicates whether request method is DELETE.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isDelete() instead
	 */
	public function isDelete()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isDelete() instead' );
		return $this->method->isDelete();
	}

	/**
	 *	Indicates whether request method is HEAD.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isHead() instead
	 */
	public function isHead()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isHead() instead' );
		return $this->method->isHead();
	}

	/**
	 *	Indicates whether request method is OPTIONS.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isOptions() instead
	 */
	public function isOptions()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isOptions() instead' );
		return $this->method->isOptions();
	}

	/**
	 *	Indicates whether request method is POST.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isPost() instead
	 */
	public function isPost()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isPost() instead' );
		return $this->method->isPost();
	}

	/**
	 *	Indicates whether request method is PUT.
	 *	@access		public
	 *	@return		boolean
	 * 	@deprecated use $request->getMethod()->isPut() instead
	 */
	public function isPut()
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->isPut() instead' );
		return $this->method->isPut();
	}

	public function remove( $key )
	{
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

	public function setAjax( $isAjax = TRUE )
	{
		$field	= new Net_HTTP_Header_Field( 'X-Requested-With', 'XMLHttpRequest' );
		if( $isAjax )
			$this->headers->addField( $field );
		else
			$this->headers->removeField( $field );
	}

	/**
	 *	Set request method.
	 *	@access		public
	 *	@param		string		$method		Request method to set
	 *	@return		self
	 * 	@deprecated use $request->getMethod()->set() instead
	 */
	public function setMethod( $method )
	{
		Deprecation::getInstance()
			->setErrorVersion( '0.8.4.7' )
			->setExceptionVersion( '0.9' )
			->message( 'Please use $request->getMethod()->set() instead' );
		$this->method->set( $method );
		return $this;
	}
}
?>

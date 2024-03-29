<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for HTTP Requests.
 *
 *	Copyright (c) 2007-2023 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\ADT\URL;
use CeusMedia\Common\Net\HTTP\Header\Field as HeaderField;
use CeusMedia\Common\Net\HTTP\Header\Section as HeaderSection;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Handler for HTTP Requests.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2023 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Finish implementation: this is bastard of request and response
 */
class Request extends Dictionary
{
	/** @var		HeaderSection		$headers		Object of collected HTTP Headers */
	public $headers;

	/** @var		string				$body			Raw POST/PUT data, if available */
	protected $body			= '';

	/**	@var		string				$ip				IP of Request */
	protected $ip			= '';

	/** @var		Method				$method			HTTP request method object */
	protected $method;

	protected $protocol		= 'HTTP';

	protected $status		= '200 OK';

	protected $version		= '1.0';

	protected $root;

	protected $path			= '/';

	protected $sources;

	public function __construct( ?string $protocol = NULL, ?string $version = NULL )
	{
		parent::__construct();
		$this->method	= new Method();
		$this->headers	= new HeaderSection();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
	}

	/**
	 *	Adds an HTTP header object.
	 *	@access		public
	 *	@param		HeaderField		$field		HTTP header field object
	 *	@return		self
	 */
	public function addHeader( HeaderField $field ): self
	{
		$this->headers->addField( $field );
		return $this;
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string			$name		HTTP header name
	 *	@param		string			$value		HTTP header value
	 *	@return		self
	 */
	public function addHeaderPair( string $name, string $value ): self
	{
		$this->headers->addField( new HeaderField( $name, $value ) );
		return $this;
	}

	public function fromEnv( bool $useSession = FALSE, bool $useCookie = FALSE ): self
	{
		//  store HTTP method
		$this->method->set( getEnv( 'REQUEST_METHOD' ) );

		$this->sources	= [
			"GET"		=> &$_GET,
			"POST"		=> &$_POST,
			"FILES"		=> &$_FILES,
			"SESSION"	=> [],
			"COOKIE"	=> [],
		];
		if( $useSession )
			$this->sources['session']	=& $_SESSION;
		if( $useCookie )
			$this->sources['cookie']	=& $_COOKIE;

		//  retrieve requested path
		$this->root	= rtrim( dirname( getEnv( 'SCRIPT_NAME' ) ), '/' ).'/';
		$this->path	= substr( getEnv( 'REQUEST_URI' ), strlen( $this->root ) );
		if( strpos( $this->path, '?' ) !== FALSE )
			$this->path = substr( $this->path, 0, strpos( $this->path, '?' ) );

		/*  --  APPLY ALL SOURCES TO ONE COLLECTION OF REQUEST ARGUMENT PAIRS  --  */
		foreach( $this->sources as $values )
			$this->pairs	= array_merge( $this->pairs, $values );

		/*  --  RETRIEVE HTTP HEADERS FROM WEBSERVER ENVIRONMENT  --  */
		$this->headers->addFieldPairs( self::getAllEnvHeaders() );

		//  store IP of requesting client
		$this->ip		= getEnv( 'REMOTE_ADDR' );
		if( $this->headers->hasField( 'X-Forwarded-For' ) )											//  request has been forwarded
			$this->ip = $this->headers->getFieldsByName( 'X-Forwarded-For', TRUE );					//  get original IP address of request

		//  store raw POST, PUT or FILE data
		$this->body	= file_get_contents( "php://input" );
		return $this;
	}

	public function fromString( string $request ): self
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		throw new Exception( 'Not implemented' );
//		return $this;
	}

	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		string		$source			Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@param		boolean		$asDictionary	Flag: return map as dictionary
	 *	@param		boolean		$strict			Flag: throw exception for invalid source, otherwise return NULL
	 *	@return		Dictionary|array			Pairs in source (or empty array if not set on strict is off)
	 *	@throws		InvalidArgumentException	if key is not set in source and strict is on
	 */
	public function getAllFromSource( string $source, bool $asDictionary = FALSE, bool $strict = TRUE )
	{
		$source	= strtoupper( $source );
		if( isset( $this->sources[$source] ) ){
			if( $asDictionary )
				return new Dictionary( $this->sources[$source] );
			return $this->sources[$source];
		}
		if( $strict )
			throw new InvalidArgumentException( 'Invalid source "'.$source.'"' );
		return [];
	}

	static public function getAllEnvHeaders(): array
	{
		if( function_exists( 'getallheaders' ) )
			return getallheaders();

		$headers		= [];
		$copyDirectly	= [
			'CONTENT_TYPE'   => 'Content-Type',
			'CONTENT_LENGTH' => 'Content-Length',
			'CONTENT_MD5'    => 'Content-Md5',
		];

		foreach( $_SERVER as $key => $value ){
			if( substr( $key, 0, 5 ) === 'HTTP_' ){
				$key	= substr( $key, 5 );
				if( !(isset( $copyDirectly[$key] ) && isset( $_SERVER[$key] ) ) ){
					$key	= strtolower( str_replace( '_', ' ', $key ) );
					$key	= str_replace( ' ', '-', ucwords( $key ) );
					$headers[$key]	= $value;
				}
			}
			elseif( isset( $copyDirectly[$key] ) ){
				$headers[$copyDirectly[$key]]	= $value;
			}
		}
		if( !isset( $headers['Authorization'] ) ){
			if( isset( $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ) ){
				$headers['Authorization']	= $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
			}
			elseif( isset( $_SERVER['PHP_AUTH_DIGEST'] ) ){
				$headers['Authorization']	= $_SERVER['PHP_AUTH_DIGEST'];
			}
			elseif( isset( $_SERVER['PHP_AUTH_USER'] ) ){
				$password	= $_SERVER['PHP_AUTH_PW'] ?? '';
				$hash		= base64_encode( $_SERVER['PHP_AUTH_USER'] . ':' . $password );
				$headers['Authorization']	= 'Basic ' . $hash;
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
	public function getFromSource( string $key, string $source, bool $strict = FALSE )
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
	 *	Returns the latest field if more than one.
	 *	Alias for getHeadersByName with TRUE as seconds parameter.
	 *	But throws an exception if nothing found and strict mode enabled (enabled by default).
	 *	@access		public
	 *	@param		string		$name		Key name of header
	 *	@param		boolean		$strict		Flag: throw exception if nothing found
	 *	@return		HeaderField|NULL
	 *	@throws		RuntimeException		if nothing found and strict mode enabled
	 */
	public function getHeader( string $name, bool $strict = TRUE ): ?HeaderField
	{
		/** @var HeaderField|NULL $header */
		$header	= $this->getHeadersByName( $name, TRUE );
		if( NULL === $header && $strict )
			throw new RuntimeException( sprintf( 'No header set by name "%s"', $name ) );
		return $header;
	}

	/**
	 *	Returns collection of all HTTP headers received.
	 *	@access		public
	 *	@return		HeaderSection			Collection of HTTP header field instances
	 */
	public function getHeaders(): HeaderSection
	{
		return $this->headers;
	}

	/**
	 *	Returns list of HTTP header fields with a specified header name.
	 *	With second parameter only the latest header field will be return, NULL if none.
	 *	@access		public
	 *	@param		string			$name		Key name of header
	 *	@param		boolean			$latestOnly	Flag: return latest header field, only
	 *	@return		HeaderField[]|HeaderField|NULL	List of HTTP header fields with given header name or singe field if latestOnly
	 */
	public function getHeadersByName( string $name, bool $latestOnly = FALSE )
	{
		return $this->headers->getFieldsByName( $name, $latestOnly );
	}

	/**
	 *	Returns detected remote client IP.
	 *	@access		public
	 *	@return		string
	 */
	public function getIp(): string
	{
		return $this->ip;
	}

	/**
	 *	Return request method object.
	 *	@access		public
	 *	@return		Method
	 */
	public function getMethod(): Method
	{
		return $this->method;
	}

	/**
	 *	...
	 *	@access		public
	 *	@return		string
	 */
	public function getPath(): string
	{
		return $this->path;
	}

	/**
	 *	Returns raw request body of POST, PUT or PATCH requests.
	 *	@access		public
	 *	@return		string
	 */
	public function getRawPostData(): string
	{
		return $this->body;
	}


	/**
	 *	Get requested URL, relative of absolute.
	 *	@access		public
	 *	@param		boolean		$absolute		Flag: return absolute URL
	 *	@return		URL
	 */
	public function getUrl( bool $absolute = TRUE ): URL
	{
		$url	= new URL( getEnv( 'REQUEST_URI' ) );
		if( $absolute ){
			$url->setScheme( getEnv( 'REQUEST_SCHEME' ) );
			$url->setHost( getEnv( 'HTTP_HOST' ) );
		}
		return $url;
	}

	/**
	 *	Indicates whether a pair is existing in a request source by its key.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case sensitive) (get,post,files[,session,cookie])
	 *	@return		bool
	 */
	public function hasInSource( string $key, string $source ): bool
	{
		$source	= strtoupper( $source );
		return isset( $this->sources[$source][$key] );
	}

	public function isAjax(): bool
	{
		return $this->headers->hasField( 'X-Requested-With' );
	}

	public function setAjax( bool $isAjax = TRUE ): self
	{
		$field	= new HeaderField( 'X-Requested-With', 'XMLHttpRequest' );
		if( $isAjax )
			$this->headers->addField( $field );
		else
			$this->headers->removeField( $field );
		return $this;
	}

	/**
	 *	...
	 *	@param		string		$protocol
	 *	@return		self
	 */
	public function setProtocol( string $protocol ): self
	{
		$this->protocol	= $protocol;
		return $this;
	}

	/**
	 *	...
	 *	@param		string		$version
	 *	@return		self
	 */
	public function setVersion( string $version ): self
	{
		$this->version	= $version;
		return $this;
	}
}

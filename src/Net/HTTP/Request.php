<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Handler for HTTP Requests.
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

use ArrayAccess;
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
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			Finish implementation: this is bastard of request and response
 */
class Request implements ArrayAccess
{
	/** @var		HeaderSection	$headers		Object of collected HTTP Headers */
	public HeaderSection $headers;

	/** @var		string			$body			Raw POST/PUT data, if available */
	protected string $body			= '';

	/**	@var		string			$ip				IP of Request */
	protected string $ip			= '';

	/** @var		Method			$method			HTTP request method object */
	protected Method $method;

	protected string $protocol		= 'HTTP';

	protected string $status		= '200 OK';

	protected string $version		= '1.0';

	protected string $root			= '';

	protected string $path			= '/';

	protected array $sources		= [];

	protected Dictionary $parameters;

	/**	@var		boolean			$caseSensitive	Flag: be case-sensitive on pair keys */
	protected bool $caseSensitive	= TRUE;

	public function __construct( ?string $protocol = NULL, ?string $version = NULL )
	{
		$this->method		= new Method();
		$this->headers		= new HeaderSection();
		$this->parameters	= new Dictionary();
		if( !empty( $protocol ) )
			$this->setProtocol( $protocol );
		if( !empty( $version ) )
			$this->setVersion( $version );
	}

	/**
	 *	Adds an HTTP header object.
	 *	@access		public
	 *	@param		HeaderField		$field		HTTP header field object
	 *	@return		static
	 */
	public function addHeader( HeaderField $field ): static
	{
		$this->headers->addField( $field );
		return $this;
	}

	/**
	 *	Adds an HTTP header.
	 *	@access		public
	 *	@param		string			$name		HTTP header name
	 *	@param		string			$value		HTTP header value
	 *	@return		static
	 */
	public function addHeaderPair( string $name, string $value ): static
	{
		$this->headers->addField( new HeaderField( $name, $value ) );
		return $this;
	}

	/**
	 *	Returns size of parameters dictionary.
	 *	@access		public
	 *	@return		integer
	 */
	public function count(): int
	{
		return $this->parameters->count();
	}

	public function fromEnv( bool $useSession = FALSE, bool $useCookie = FALSE ): static
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
		if( str_contains( $this->path, '?' ) )
			$this->path = substr( $this->path, 0, strpos( $this->path, '?' ) );

		/*  --  APPLY ALL SOURCES TO ONE COLLECTION OF REQUEST ARGUMENT PAIRS  --  */
		foreach( $this->sources as $values )
			foreach( $values as $key => $value )
				$this->parameters->set( $key, $value );

		/*  --  RETRIEVE HTTP HEADERS FROM WEBSERVER ENVIRONMENT  --  */
		$this->headers->addFieldPairs( static::getAllEnvHeaders() );

		//  store IP of requesting client
		$this->ip		= getEnv( 'REMOTE_ADDR' ) ?: '';
		if( $this->headers->hasField( 'X-Forwarded-For' ) )											//  request has been forwarded
			$this->ip = (string) $this->headers->getField( 'X-Forwarded-For' )?->getValue();			//  get original IP address of request

		//  store raw POST, PUT or FILE data
		$this->body	= file_get_contents( "php://input" );
		return $this;
	}

	public function fromString( string $request ): static
	{
		/** @noinspection PhpUnhandledExceptionInspection */
		throw new Exception( 'Not implemented' );
//		return $this;
	}

	/**
	 *	Return a value of parameter dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in dictionary
	 *	@param		mixed		$default	Value to return if key is not set, default: NULL
	 *	@return		mixed
	 */
	public function get( string $key, mixed $default = NULL ): mixed
	{
		return $this->parameters->get( $key, $default );
	}

	/**
	 *	Returns all Pairs of Dictionary as an Array.
	 *	Using a filter prefix, all pairs with keys starting with prefix are returned.
	 *	Attention: A given prefix will be cut from pair keys.
	 *	By default, an array is returned. Alternatively another dictionary can be returned.
	 *	@access		public
	 *	@param		string|NULL		$prefix			Prefix to filter keys, e.g. "mail." for all pairs starting with "mail."
	 *	@param		boolean			$asDictionary	Flag: return list as dictionary object instead of an array
	 *	@param		boolean			$caseSensitive	Flag: return list with lowercase pair keys or dictionary with no case sensitivity
	 *	@return		Dictionary|array				Map or dictionary object containing all or filtered pairs
	 */
	public function getAll( string $prefix = NULL, bool $asDictionary = FALSE, bool $caseSensitive = TRUE ): Dictionary|array
	{
		return $this->parameters->getAll( $prefix, $asDictionary, $caseSensitive );
	}

	/**
	 *	Reads and returns Data from Sources.
	 *	@access		public
	 *	@param		string		$source			Source key (not case-sensitive) (get,post,files[,session,cookie])
	 *	@param		boolean		$asDictionary	Flag: return map as dictionary
	 *	@param		boolean		$strict			Flag: throw exception for invalid source, otherwise return NULL
	 *	@return		Dictionary|array			Pairs in source (or empty array if not set on strict is off)
	 *	@throws		InvalidArgumentException	if key is not set in source and strict is on
	 */
	public function getAllFromSource( string $source, bool $asDictionary = FALSE, bool $strict = TRUE ): array|Dictionary
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
			if( str_starts_with( $key, 'HTTP_' ) ){
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
	 *	@param		string		$source		Source key (not case-sensitive) (get,post,files[,session,cookie])
	 *	@param		bool		$strict		Flag: throw exception if not set, otherwise return NULL
	 *	@throws		InvalidArgumentException if key is not set in source and strict is on
	 *	@return		mixed		Value of key in source or NULL if not set
	 */
	public function getFromSource( string $key, string $source, bool $strict = FALSE ): mixed
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
	public function getHeadersByName( string $name, bool $latestOnly = FALSE ): array|HeaderField|NULL
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
	 *	Return list of parameter pair keys.
	 *	@access		public
	 *	@return		array		List of parameter pair keys
	 */
	public function getKeys(): array
	{
		return $this->parameters->getKeys();
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
	 *	Indicates whether a parameter key is existing.
	 *	@access		public
	 *	@param		string		$key		Parameter key in dictionary
	 *	@return		boolean
	 */
	public function has( string $key ): bool
	{
		return $this->parameters->has( $key );
	}

	/**
	 *	Indicates whether a pair is existing in a request source by its key.
	 *	@access		public
	 *	@param		string		$key		...
	 *	@param		string		$source		Source key (not case-sensitive) (get,post,files[,session,cookie])
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

	/**
	 *	Indicates whether a Key is existing.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		boolean
	 */
	public function offsetExists( $offset ): bool
	{
		return $this->parameters->has( $offset );
	}

	/**
	 *	Return a Value of Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		mixed
	 */
	public function offsetGet( $offset ): mixed
	{
		return $this->parameters->get( $offset );
	}

	/**
	 *	Sets Value of Key in Dictionary.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@param		string		$value		Value of Key
	 *	@return		void
	 */
	public function offsetSet( $offset, $value ): void
	{
		$this->parameters->set( $offset, $value );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$offset		Key in Dictionary
	 *	@return		void
	 */
	public function offsetUnset( $offset ): void
	{
		$this->parameters->remove( $offset );
	}

	/**
	 *	Removes a Value from Dictionary by its Key.
	 *	@access		public
	 *	@param		string		$key		Key in Dictionary
	 *	@return		static
	 */
	public function remove( string $key ): static
	{
		$this->parameters->remove( $key );
		return $this;
	}

	public function set( string $key, mixed $value ): static
	{
		$this->parameters->set( $key, $value );
		return $this;
	}

	public function setAjax( bool $isAjax = TRUE ): static
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
	 *	@return		static
	 */
	public function setProtocol( string $protocol ): static
	{
		$this->protocol	= $protocol;
		return $this;
	}

	/**
	 *	...
	 *	@param		string		$version
	 *	@return		static
	 */
	public function setVersion( string $version ): static
	{
		$this->version	= $version;
		return $this;
	}
}

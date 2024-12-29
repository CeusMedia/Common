<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	HTTP method data type.
 *
 *	Copyright (c) 2020 Christian Würker (ceusmedia.de)
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
 *	@copyright		2020 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\HTTP;

use BadMethodCallException;

/**
 *	HTTP method data type.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_HTTP
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2020 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@see			https://developer.mozilla.org/en-US/docs/Web/HTTP/Methods
 */
class Method
{
	public const METHOD_CONNECT		= 'CONNECT';
	public const METHOD_DELETE		= 'DELETE';
	public const METHOD_GET			= 'GET';
	public const METHOD_HEAD		= 'HEAD';
	public const METHOD_OPTIONS		= 'OPTIONS';
	public const METHOD_PATCH		= 'PATCH';
	public const METHOD_POST		= 'POST';
	public const METHOD_PUT			= 'PUT';
	public const METHOD_TRACE		= 'TRACE';

	/** @var		string			$method			HTTP request method */
	protected string $method		= self::METHOD_GET;

	public static array $methods	= [
		self::METHOD_CONNECT,
		self::METHOD_DELETE,
		self::METHOD_GET,
		self::METHOD_HEAD,
		self::METHOD_OPTIONS,
		self::METHOD_PATCH,
		self::METHOD_POST,
		self::METHOD_PUT,
		self::METHOD_TRACE,
	];

	public function __construct( string $method = NULL )
	{
		if( $method )
			$this->set( $method );
	}

	public function __toString(): string
	{
		return $this->get();
	}

	/**
	 *	Return request method.
	 *	@access		public
	 *	@return		string
	 */
	public function get(): string
	{
		return $this->method;
	}

	/**
	 *	Indicate whether a specific request method is used.
	 *	Method parameter is not case-sensitive.
	 *	@access		public
	 *	@param		string		$method		Request method to check against
	 *	@return		boolean
	 */
	public function is( string $method ): bool
	{
		return strtoupper( trim( $method ) ) === $this->method;
	}

	/**
	 *	Indicates whether request method is CONNECT.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isConnect(): bool
	{
		return $this->is( static::METHOD_CONNECT );
	}

	/**
	 *	Indicates whether request method is GET.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isGet(): bool
	{
		return $this->is( static::METHOD_GET );
	}

	/**
	 *	Indicates whether request method is DELETE.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isDelete(): bool
	{
		return $this->is( static::METHOD_DELETE );
	}

	/**
	 *	Indicates whether request method is HEAD.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isHead(): bool
	{
		return $this->is( static::METHOD_HEAD );
	}

	/**
	 *	Indicates whether request method is OPTIONS.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isOptions(): bool
	{
		return $this->is( static::METHOD_OPTIONS );
	}

	/**
	 *	Indicates whether request method is PATCH.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isPatch(): bool
	{
		return $this->is( static::METHOD_PATCH );
	}

	/**
	 *	Indicates whether request method is POST.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isPost(): bool
	{
		return $this->is( static::METHOD_POST );
	}

	/**
	 *	Indicates whether request method is PUT.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isPut(): bool
	{
		return $this->is( static::METHOD_PUT );
	}

	/**
	 *	Indicates whether request method is TRACE.
	 *	@access		public
	 *	@return		boolean
	 */
	public function isTrace(): bool
	{
		return $this->is( static::METHOD_TRACE );
	}

	/**
	 *	Set request method.
	 *	@access		public
	 *	@param		string		$method		Request method to set
	 *	@return		static
	 *	@throws		BadMethodCallException	if given method is not supported
	 */
	public function set( string $method ): static
	{
		$method		= strtoupper( $method );
		if( !in_array( $method, static::$methods ) )
			throw new BadMethodCallException( 'HTTP method "%s" is not supported' );
		$this->method	= $method;
		return $this;
	}
}

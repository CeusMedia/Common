<?php /** @noinspection PhpUnused */
/** @noinspection PhpComposerExtensionStubsInspection */
/** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Premailer API PHP class.
 *
 *	Copyright (c) 2012-2024 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_API
 *	@copyright		2012-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@author			Marcus Bointon <marcus@synchromedia.co.uk>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://premailer.dialect.ca/api
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\API;

use CeusMedia\Common\ADT\JSON\Encoder as JsonEncoder;
use CeusMedia\Common\Exception\IO as IoException;
use CeusMedia\Common\Net\HTTP\Post;
use CeusMedia\Common\Net\Reader as NetReader;
use Exception;
use Psr\SimpleCache\CacheInterface as SimpleCacheInterface;
use Psr\SimpleCache\InvalidArgumentException as SimpleCacheInvalidArgumentException;
use RuntimeException;

/**
 *	Premailer API PHP class.
 *
 *	Premailer is a library/service for making HTML more palatable for various inept email clients, in particular GMail
 *	Primary function is to convert style tags into equivalent inline styles so styling can survive <head> tag removal
 *	Premailer is owned by Dialect Communications group
 *
 *	Forked from https://github.com/alexdunae/premailer/
 *
 *	@category		Library
 *	@package		CeusMedia_Common_Net_API
 *	@copyright		2012-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@author			Marcus Bointon <marcus@synchromedia.co.uk>
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://premailer.dialect.ca/api
 *	@link			https://github.com/CeusMedia/Common
 */
class Premailer
{
	protected const ENDPOINT = 'https://premailer.dialect.ca/api/0.1/documents';

	protected ?SimpleCacheInterface $cache	= NULL;

	protected mixed $response;

	public static array $options = [
		//  string  - Which document handler to use (hpricot (default) or nokigiri)
		'adaptor'			=> 'hpricot',
		//  string  - Base URL for converting relative links
		'base_url'			=> '',
		//  integer - Length of lines in the plain text version (default 65)
		'line_length'		=> 65,
		//  string  - Query string appended to links
		'link_query_string'	=> '',
		//  boolean - Whether to preserve any link rel=stylesheet and style elements
		'preserve_styles'	=> true,
		//  boolean - Remove IDs from the HTML document?
		'remove_ids'		=> false,
		//  boolean - Remove classes from the HTML document?
		'remove_classes'	=> false,
		//  boolean - Remove comments from the HTML document?
		'remove_comments'	=> false
	];

	public function __construct( ?SimpleCacheInterface $cache = NULL )
	{
		if( $cache )
			$this->setCache( $cache );
	}

	/**
	 *	...
	 *	@param		array		$params
	 *	@return		mixed
	 *	@throws		Exception
	 *	@throws		SimpleCacheInvalidArgumentException
	 */
	protected function convert( array $params ): mixed
	{
		if( !$params['base_url'] )
			unset( $params['base_url'] );
		if( !$params['link_query_string'] )
			unset( $params['link_query_string'] );
		$params['preserve_styles']	= (bool) $params['preserve_styles'];
		$params['remove_ids']		= (bool) $params['remove_ids'];
		$params['remove_classes']	= (bool) $params['remove_classes'];
		$params['remove_comments']	= (bool) $params['remove_comments'];
		$params['fetchresult']		= true;

		$requestId	= md5( JsonEncoder::create()->encode( $params ) );
		$cacheKey	= 'premailer_'.$requestId.'.data';
		if( $this->cache && $this->cache->has( $cacheKey ) )
			return json_decode( $this->cache->get( $cacheKey ) );

		$request	= new Post( self::ENDPOINT );
		$request->setContent( Post::convertArrayToFormData( $params ) );
		$response	= json_decode( $request->send( [
			CURLOPT_TIMEOUT			=> 15,
			CURLOPT_USERAGENT		=> 'PHP Premailer',
			CURLOPT_SSL_VERIFYHOST	=> 0,
			CURLOPT_SSL_VERIFYPEER	=> 0,
		] ) );
		if( $response->status != 201 ){
			throw match( $response->status ){
				400		=> new Exception( 'Content missing', 400 ),
				403		=> new Exception( 'Access forbidden', 403 ),
				default	=> new Exception( 'Error', $response->status ),
			};
		}
		$response->requestId	= $requestId;
		$this->cache && $this->cache->set( $cacheKey, JsonEncoder::create()->encode( $response ) );
		return $this->response	= $response;
	}

	/**
	 *	Submit URL to HTML resource to be converted.
	 *	The returned response object contains URLs to the converted resources.
	 *	@access		public
	 *	@param		string		$url		URL to HTML resource to be converted
	 *	@param		array		$params		Conversion parameters
	 *	@return		object		Response object
	 *	@throws		Exception
	 *	@throws		SimpleCacheInvalidArgumentException
	 */
	public function convertFromUrl( string $url, array $params = [] ): object
	{
		$params = array_merge( self::$options, $params );
		$params['url']	= $url;
		$this->response	= $this->convert( $params );
		return $this->response;
	}

	/**
	 *	Submit HTML content to be converted.
	 *	The returned response object contains URLs to the converted resources.
	 *	@access		public
	 *	@param		string		$html		HTML content to be converted
	 *	@param		array		$params		Conversion parameters
	 *	@return		object		Response object
	 *	@throws		Exception
	 *	@throws		SimpleCacheInvalidArgumentException
	 */
	public function convertFromHtml( string $html, array $params = [] ): object
	{
		$params = array_merge( self::$options, $params );
		$params['html']	= $html;
		$this->response	= $this->convert( $params );
		return $this->response;
	}

	/**
	 *	Returns converted HTML.
	 *	@access		public
	 *	@return		string		Converted HTML
	 *	@throws		IoException
	 *	@throws		SimpleCacheInvalidArgumentException
	 */
	public function getHtml(): string
	{
		if( !$this->response )
			throw new RuntimeException( 'No conversion started' );
		$cacheKey	= 'premailer_'.$this->response->requestId.'.html';
		if( $this->cache && $this->cache->has( $cacheKey ) )
			return $this->cache->get( $cacheKey );
		$html	= NetReader::readUrl( $this->response->documents->html );
		$this->cache && $this->cache->set( $cacheKey, $html );
		return $html;
	}

	/**
	 *	Returns converted plain text.
	 *	@access		public
	 *	@return		string		Converted HTML
	 *	@throws		IoException
	 *	@throws		SimpleCacheInvalidArgumentException
	 */
	public function getPlainText(): string
	{
		if( !$this->response )
			throw new RuntimeException( 'No conversion startet' );
		$cacheKey	= 'premailer_'.$this->response->requestId.'.text';
		if( $this->cache && $this->cache->has( $cacheKey ) )
			return $this->cache->get( $cacheKey );
		$text	= NetReader::readUrl( $this->response->documents->txt );
		$this->cache && $this->cache->set( $cacheKey, $text );
		return $text;
	}

	public function setCache( SimpleCacheInterface $cache ): self
	{
		$this->cache	= $cache;
		return $this;
	}
}

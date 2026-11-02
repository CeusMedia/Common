<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

declare(strict_types=1);

/**
 *	Crawls and counts all internal Links of an URL.
 *
 *	Copyright (c) 2007-2026 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\Site;

use CeusMedia\Common\Alg\UnitFormater;
use CeusMedia\Common\Net\Reader as NetReader;
use DOMDocument;
use Exception;
use InvalidArgumentException;
use RuntimeException;

/**
 *	Crawls and counts all internal Links of an URL.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2026 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 *	@todo			finish Code Doc
 */
class Crawler
{
	protected string $baseUrl;
	protected bool $crawled		= FALSE;
	protected int $depth		= 10;
	protected array $errors		= [];
	protected array $links		= [];

	protected string $host;
	protected string $pass;
	protected string $path;
	protected string $port;
	protected string $scheme;
	protected string $user;

	/**	@var	string			$cookieFile		Temporary cookie file while crawling */
	protected string $cookieFile	= '';

	public array $denied			= [
		'pdf',
		'doc',
		'xls',
		'ppt',
		'mp3',
		'mp4',
		'mpeg',
		'mpg',
		'avi',
		'mov',
		'jpg',
		'jpeg',
		'gif',
		'png',
		'bmp',
	];

	public array $deniedUrlParts	= [];

	/**	@var	NetReader		$reader */
	protected NetReader $reader;

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		string		$baseUrl
	 *	@param		integer		$depth			Number of Links followed in a Row
	 *	@return		void
	 */
	public function __construct( string $baseUrl, int $depth = 10 )
	{
		if( $depth < 1 )
			throw new InvalidArgumentException( 'Depth must be at least 1.' );
		$this->baseUrl	= $baseUrl;
		$this->depth	= $depth;
		$this->reader	= new NetReader( "empty" );
		$this->reader->setUserAgent( "SiteCrawler/0.1" );
	}

	/**
	 *	Builds URL from URL parts.
	 *	@access		protected
	 *	@param		array		$parts			Parts of URL
	 *	@return		string
	 */
	protected function buildUrl( array $parts ): string
	{
		$url	= ( $parts['scheme'] ?? 'http' ).'://';
		if( !empty( $parts['user'] ) )
			$url	.= $parts['user'].( isset( $parts['pass'] ) && '' !== $parts['pass'] ? ':'.$parts['pass'] : '' ).'@';
		$url	.= $parts['host'] ?? '';
		if( !empty( $parts['port'] ) )
			$url	.= ':'.$parts['port'];
		$path	= (string) ( $parts['path'] ?? '' );
		if( !str_starts_with( $path, '/' ) )
			$path	= '/'.$path;
		$url	.= (string) preg_replace( '@/{2,}@', '/', $path );
		if( isset( $parts['query'] ) && '' !== $parts['query'] )
			$url	.= '?'.$parts['query'];
		return $url;
	}

	/**
	 *	Crawls a Web Page and follows its links up to the set depth.
	 *	@access		public
	 *	@param		string		$url					URL of Web Page to start at
	 *	@param		boolean		$followExternalLinks	Flag: follow external Links (on another Domain)
	 *	@param		boolean		$followWithLabelOnly	Flag: follow link with label only
	 *	@param		boolean		$verbose				Flag: show Progression
	 *	@return		integer		Number of found pages
	 */
	public function crawl( string $url, bool $followExternalLinks = FALSE, bool $followWithLabelOnly = FALSE, bool $verbose = FALSE ): int
	{
		$this->crawled	= FALSE;
		$this->errors	= [];
		$this->links	= [];
		$parts			= parse_url( $url ) ?: [];
		$this->scheme	= $parts['scheme'] ?? 'http';
		$this->host		= $parts['host'] ?? '';
		$this->port		= (string) ( $parts['port'] ?? '' );
		$this->user		= $parts['user'] ?? '';
		$this->pass		= $parts['pass'] ?? '';
		$this->path		= $parts['path'] ?? '';
		$this->cookieFile	= (string) tempnam( sys_get_temp_dir(), 'SiteCrawler' );

		$number		= 0;
		$visited	= [];
		$queue		= [[$this->buildUrl( $parts ), 1]];
		try{
			while( [] !== $queue ){
				[$url, $level]	= array_shift( $queue );
				if( isset( $visited[$url] ) ){
					$this->handleRecoveredLink( $url );
					continue;
				}
				$visited[$url]	= TRUE;
				if( !$followExternalLinks && !str_starts_with( $url, $this->baseUrl ) )
					continue;
				$denied	= str_contains( (string) parse_url( $url, PHP_URL_PATH ), '..' );
				foreach( $this->deniedUrlParts as $part )
					if( preg_match( "@".$part."@", $url ) )
						$denied	= TRUE;
				if( $denied )
					continue;
				try{
					$number++;
					$content	= $this->getHTML( $url );
					if( '' === $content )
						continue;
					$this->handleRecoveredLink( $url, $content );
					if( $verbose )
						$this->handleVerbose( $url, $number );
					if( $level >= $this->depth )
						continue;
					$document	= $this->getDocument( $content, $url );
					foreach( $this->getLinksFromDocument( $document, $followWithLabelOnly, $url ) as $link => $label ){
						$extension	= strtolower( pathinfo( (string) parse_url( $link, PHP_URL_PATH ), PATHINFO_EXTENSION ) );
						if( '' !== $extension && in_array( $extension, $this->denied, TRUE ) )
							continue;
						$queue[]	= [$link, $level + 1];
					}
				}
				catch( Exception $e ){
					$this->errors[$url]	= $e->getMessage();
				}
			}
		}
		finally{
			@unlink( $this->cookieFile );
		}
		$this->crawled	= TRUE;
		return count( $this->links );
	}

	/**
	 *	Returns base URL for relative links of a document: its base element or the given page URL or the base URL of crawler.
	 *	@access		protected
	 *	@param		DOMDocument|NULL	$document		DOM Document of HTML page
	 *	@param		string|NULL			$pageUrl		URL of HTML page
	 *	@return		string
	 */
	protected function getBaseUrl( ?DOMDocument $document = NULL, ?string $pageUrl = NULL ): string
	{
		$url	= $pageUrl ?? $this->buildUrl( parse_url( $this->baseUrl ) ?: [] );
		if( $document ){
			$base	= $document->getElementsByTagName( "base" );
			if( $base->length && '' !== trim( $base->item( 0 )->getAttribute( 'href' ) ) )
				$url	= static::resolveUrl( $url, trim( $base->item( 0 )->getAttribute( 'href' ) ) );
		}
		return $url;
	}

	/**
	 *	Returns DOM Document of HTML Content.
	 *	@access		protected
	 *	@param		string		$content		HTML Content
	 *	@param		string		$url			URL of HTML Page
	 *	@return		DOMDocument
	 */
	protected function getDocument( string $content, string $url ): DOMDocument
	{
		$document	= new DOMDocument();
		$useErrors	= libxml_use_internal_errors( TRUE );
		$loaded		= $document->loadHTML( $content );
		libxml_clear_errors();
		libxml_use_internal_errors( $useErrors );
		if( !$loaded )
			throw new RuntimeException( 'Error reading HTML.' );
		return $document;
	}

	/**
	 *	Returns List of Errors.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 *	Reads HTML Page and returns Content, or empty string if page is not HTML.
	 *	@access		protected
	 *	@param		string		$url		URL to get Content for
	 *	@return		string
	 */
	protected function getHTML( string $url ): string
	{
		$this->reader->setUrl( $url );
		try{
			$content	= $this->reader->read( [
				CURLOPT_FOLLOWLOCATION	=> TRUE,
				CURLOPT_COOKIEJAR		=> $this->cookieFile,
				CURLOPT_COOKIEFILE		=> $this->cookieFile,
			] );
			$contentType	= strtolower( trim( (string) $this->reader->getInfo( 'content_type' ) ) );
			return str_starts_with( $contentType, 'text/html' ) ? $content : '';
		}
		catch( RuntimeException $e ){
			$this->errors[$url]	= $e->getMessage();
			throw $e;
		}
	}

	/**
	 *	Returns List of found URLs with Document Content.
	 *	@access		public
	 *	@return		array
	 */
	public function getLinks(): array
	{
		if( !$this->crawled )
			throw new RuntimeException( "First crawl an URL." );
		return $this->links;
	}

	/**
	 *	Parses a HTML Document and returns extracted Link URLs, resolved to absolute URLs without fragments.
	 *	@access		protected
	 *	@param		DOMDocument	$document		DOM Document of HTML Content
	 *	@param		boolean		$onlyWithLabel	Flag: note only links with label
	 *	@param		string|NULL	$pageUrl		URL of HTML page, to resolve relative links
	 *	@return		array<string,string>		Map of URLs and labels
	 */
	protected function getLinksFromDocument( DOMDocument $document, bool $onlyWithLabel = FALSE, ?string $pageUrl = NULL ): array
	{
		$baseUrl	= $this->getBaseUrl( $document, $pageUrl );
		$links		= [];
		foreach( $document->getElementsByTagName( "a" ) as $node ){
			$ref	= trim( $node->getAttribute( 'href' ) );
			if( '' === $ref || preg_match( "@^(#|mailto:|javascript:|tel:|data:)@i", $ref ) )
				continue;
			$url	= static::resolveUrl( $baseUrl, $ref );
			if( !preg_match( "@^https?://@i", $url ) )
				continue;
			$label	= trim( strip_tags( $node->nodeValue ?? '' ) );
			if( $node->hasAttribute( 'title' ) )
				$label	= trim( strip_tags( $node->getAttribute( 'title' ) ) );
			if( $onlyWithLabel && !strlen( $label ) )
				continue;
			$links[$url]	= $label;
		}
		return $links;
	}

	/**
	 *	Resolves a (relative) link against a base URL and removes the fragment.
	 *	@access		public
	 *	@static
	 *	@param		string		$baseUrl		Absolute URL of page or base
	 *	@param		string		$ref			Link to resolve
	 *	@return		string
	 */
	public static function resolveUrl( string $baseUrl, string $ref ): string
	{
		$ref	= (string) preg_replace( '/#.*$/', '', $ref );
		if( preg_match( '@^[a-z][a-z0-9+.-]*:@i', $ref ) )
			return $ref;
		$base	= parse_url( $baseUrl ) ?: [];
		$prefix	= ( $base['scheme'] ?? 'http' ).'://';
		if( str_starts_with( $ref, '//' ) )
			return ( $base['scheme'] ?? 'http' ).':'.$ref;
		if( !empty( $base['user'] ) )
			$prefix	.= $base['user'].( !empty( $base['pass'] ) ? ':'.$base['pass'] : '' ).'@';
		$prefix	.= ( $base['host'] ?? '' ).( !empty( $base['port'] ) ? ':'.$base['port'] : '' );
		if( '' === $ref )
			return $prefix.( $base['path'] ?? '/' ).( isset( $base['query'] ) ? '?'.$base['query'] : '' );
		$query	= '';
		if( str_contains( $ref, '?' ) ){
			[$ref, $query]	= explode( '?', $ref, 2 );
			$query	= '?'.$query;
		}
		if( '' === $ref )
			$path	= $base['path'] ?? '/';
		else if( str_starts_with( $ref, '/' ) )
			$path	= $ref;
		else{
			$basePath	= $base['path'] ?? '/';
			$path		= substr( $basePath, 0, (int) strrpos( $basePath, '/' ) + 1 ).$ref;
		}
		//  remove dot segments
		$segments	= [];
		foreach( explode( '/', $path ) as $segment ){
			if( '.' === $segment )
				continue;
			if( '..' === $segment ){
				if( count( $segments ) > 1 )
					array_pop( $segments );
				continue;
			}
			$segments[]	= $segment;
		}
		$path	= implode( '/', $segments );
		if( str_ends_with( $ref, '/.' ) || str_ends_with( $ref, '/..' ) || '.' === $ref || '..' === $ref )
			$path	.= '/';
		if( !str_starts_with( $path, '/' ) )
			$path	= '/'.$path;
		return $prefix.$path.$query;
	}

	/**
	 *	Prints progress of crawling.
	 *	@access		protected
	 *	@param		string		$url			URL of Web Page
	 *	@param		integer		$number			Number of followed Page
	 *	@return		void
	 */
	protected function handleVerbose( string $url, int $number ): void
	{
		$speed	= UnitFormater::formatBytes( (float) $this->reader->getInfo( 'speed_download' ) );
		remark( "[".$number."] ".$url." | ".$speed."/s" );
	}

	/**
	 *	Notes found URL and its content, or increases number of references if URL has been noted before.
	 *	@access		protected
	 *	@param		string			$url			URL of Web Page
	 *	@param		string|NULL		$content		HTML Content of Web Page
	 *	@return		void
	 */
	protected function handleRecoveredLink( string $url, ?string $content = NULL ): void
	{
		$key	= base64_encode( $url );
		if( array_key_exists( $key, $this->links ) ){
			$this->links[$key]['references']++;
			return;
		}
		if( NULL === $content )
			return;
		$this->links[$key] = [
			'url'			=> $url,
			'references'	=> 1,
			'content'		=> $content
		];
	}
}

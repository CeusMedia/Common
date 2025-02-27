<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Crawls and counts all internal Links of an URL.
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
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2024 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\Site;

use CeusMedia\Common\ADT\Collection\Dictionary;
use CeusMedia\Common\ADT\StringBuffer;
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
 *	@copyright		2007-2024 Christian Würker
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
	 *	Builds URL from Parts.
	 *	@access		protected
	 *	@param		array		$parts			Parts of URL
	 *	@return		string
	 */
	protected function buildUrl( array $parts ): string
	{
		$url	= new StringBuffer();
		if( isset( $parts['user'] ) && isset( $parts['pass'] ) && $parts['user'] )
			$url->append( $parts['user'].":".$parts['pass']."@" );
		if( substr( $parts['path'], 0, 1 ) != "/" )
			$parts['path']	= "/".$parts['path'];
		$host	= $parts['host'].( !empty( $parts['port'] ) ? ":".$parts['port'] : "" );
		$url->append( $host.$parts['path'] );
		if( isset( $parts['query'] ) )
			$url->append( "?".$parts['query'] );
		$url	= str_replace( "//", "/", $url->toString() );
		$url	= $parts['scheme']."://".$url;
		return $url;
	}

	/**
	 *	Crawls a Website, collects Information and returns Number of visited Links.
	 *	@access		public
	 *	@param		string		$url					URL of Web Page to start at
	 *	@param		boolean		$followExternalLinks	Flag: follow external Links (on another Domain)
	 *	@param		boolean		$followWithLabelOnly	Flag: follow link with label only
	 *	@param		boolean		$verbose				Flag: show Progression
	 *	@return		integer
	 */
	public function crawl( string $url, bool $followExternalLinks = FALSE, bool $followWithLabelOnly = FALSE, bool $verbose = FALSE ): int
	{
		//  XDebug Profiler is enabled
		if( $xdebug = ini_get( 'xdebug.profiler_enable' ) )
			//  disable Profiler
			ini_set( 'xdebug.profiler_enable', "0" );

		$this->crawled	= FALSE;
		$this->errors	= [];
		$this->links	= [];
		$parts			= parse_url( $url );
		$this->scheme	= $parts['scheme'] ?? "";
		$this->host		= $parts['host'] ?? "";
		$this->port		= $parts['port'] ?? "";
		$this->user		= $parts['user'] ?? "";
		$this->pass		= $parts['pass'] ?? "";
		$this->path		= $parts['path'] ?? "";

		$number		= 0;
		$urlList	= [$url];
		while( count( $urlList ) ){
			$number++;
			$url	= array_shift( $urlList );
			$parts	= new Dictionary( parse_url( $url ) );

			$parts['scheme']	= $this->scheme;
			$parts['host']		??= $this->host;
			$parts['port']		= $this->port;
			$parts['user']		= $this->user;
			$parts['pass']		= $this->pass;
#			$parts['path']		= $this->path.$parts['path'];

			if( substr( $url, 0, strlen( $this->baseUrl ) ) !== $this->baseUrl )
				if( !$followExternalLinks )
					continue;
			$url		= $this->buildUrl( $parts->getAll() );
			if( array_key_exists( base64_encode( $url ), $this->links ) )
				continue;

			$denied				= FALSE;
			foreach( $this->deniedUrlParts as $part )
				if( preg_match( "@".$part."@", $url ) )
					$denied	= TRUE;

			if( $denied || substr_count( $parts['path'], ".." ) )
				continue;

			try{
				$content	= $this->getHTML( $url );
				if( $content ){
					$this->handleRecoveredLink( $url, $content );
					if( $verbose )
						$this->handleVerbose( $url, $number );
					$document	= $this->getDocument( $content, $url );
					$links		= $this->getLinksFromDocument( $document, $followWithLabelOnly );
					foreach( $links as $url => $label ){
						$info	= pathinfo( $url );
						if( isset( $info['extension'] ) )
							if( in_array( strtolower( $info['extension'] ), $this->denied ) )
								continue;
						$urlList[]	= $url;
					}
				}
			}
			catch( Exception $e ){
				$this->errors[$url]	= $e->getMessage();
			}
		}
		$this->crawled	= TRUE;
		//  XDebug Profiler was enabled
		if( $xdebug )
			//  enable Profiler
			ini_set( 'xdebug.profiler_enable', "1" );
		return count( $this->links );
	}

	protected function getBaseUrl( DOMDocument $document = NULL ): string
	{
		$parts	= parse_url( $this->baseUrl );
		$url	= $this->buildUrl( $parts );
		if( $document ){
			$base	= $document->getElementsByTagName( "base" );
			if( $base->length )
				$url	= $base->item( 0 )->getAttribute( 'href' );
		}
		return $url;
	}

	/**
	 *	Tries to get DOM Document from HTML Content or logs Errors and throws Exception.
	 *	@access		public
	 *	@param		string		$content		HTML Content
	 *	@param		string		$url			URL of HTML Page
	 *	@return		DOMDocument
	 */
	protected function getDocument( string $content, string $url ): DOMDocument
	{
		$doc = new DOMDocument();
		ob_start();
		if( !@$doc->loadHTML( $content ) ){
			$content	= ob_get_clean();
			if( $content )
				$this->errors[$url]	= $content;
			throw new RuntimeException( 'Error reading HTML.' );
		}
		ob_end_clean();
		return $doc;
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
	 *	Reads HTML Page and returns Content or logs Errors and throws Exception.
	 *	@access		public
	 *	@param		string		$url		URL to get Content for
	 *	@return		string
	 * @throws Exception
	 */
	protected function getHTML( string $url ): string
	{
		$this->reader->setUrl( $url );
		try{
			$content	= $this->reader->read( [
				CURLOPT_FOLLOWLOCATION	=> TRUE,
				CURLOPT_COOKIEJAR		=> 'cookies.txt',
				CURLOPT_COOKIEFILE		=> 'cookies.txt'
			] );
			$contentType	= $this->reader->getInfo( 'content_type' );
			return $contentType === 'text/html' ? $content : '';
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
	 *	Parses a HTML Document and returns extracted Link URLs.
	 *	@access		protected
	 *	@param		DOMDocument	$document		DOM Document of HTML Content
	 *	@param		boolean		$onlyWithLabel	Flag: note only links with label
	 *	@return		array
	 */
	protected function getLinksFromDocument( DOMDocument $document, bool $onlyWithLabel = FALSE ): array
	{
		$baseUrl	= $this->getBaseUrl( $document );
		$links		= [];
		$nodes		= $document->getElementsByTagName( "a" );
		foreach( $nodes as $node ){
			$ref	= $node->getAttribute( 'href' );
			$ref	= trim( preg_replace( "@^\./@", "", $ref ) );
			if( strlen( $ref ) ){
				$base	= $document->getElementsByTagName( "base" );
	//			remark( $ref );
				if( preg_match( "@^(#|mailto:|javascript:)@", $ref ) )
					continue;
				if( $base->length && preg_match( "@^\.\./@", $ref ) )
					continue;
				if( preg_match( "@^\.?/@", $ref ) )
					$ref	= $baseUrl.$ref;
				else if( preg_match( "@^\./@", $ref ) )
					$ref	= preg_replace( "@^\./@", "", $ref );
				if( !preg_match( "@^https?://@", $ref ) )
					$ref	= $baseUrl.$ref;
				$label	= trim( strip_tags( $node->nodeValue ) );
				if( $node->hasAttribute( 'title' ) )
					$label	= trim( strip_tags( $node->getAttribute( 'title' ) ) );
				if( $onlyWithLabel && !strlen( $label ) )
					continue;
				$links[$ref]	= $label;
			}
		}
		return $links;
	}

	/**
	 *	Shows Information about downloaded Web Page. This Method is customizable (can be overwritten).
	 *	@access		protected
	 *	@param		string		$url			URL of Web Page
	 *	@param		integer		$number			Number of followed Page
	 *	@return		void
	 */
	protected function handleVerbose( string $url, int $number )
	{
		$speed	= UnitFormater::formatBytes( $this->reader->getInfo( 'speed_download' ) );
		$url	= str_replace( "http://".$this->host.":".$this->port, "", $url );
		$url	= str_replace( "http://".$this->host, "", $url );
		remark( "[".$number."] ".$url." | ".$speed."/s" );
#		print_m( $this->reader->getStatus() );
	}

	/**
	 *	Collects URL, Number of References and Content of Web Pages. This Method is customizable (can be overwritten).
	 *	@access		protected
	 *	@param		string			$url			URL of Web Page
	 *	@param		string|NULL		$content		HTML Content of Web Page
	 *	@return		void
	 */
	protected function handleRecoveredLink( string $url, ?string $content = NULL )
	{
		if( array_key_exists( $url, $this->links ) ){
			$this->links[$url]['references']++;
			return;
		}
		$this->links[base64_encode( $url )] = [
			'url'			=> $url,
			'references'	=> 1,
			'content'		=> $content
		];
	}
}

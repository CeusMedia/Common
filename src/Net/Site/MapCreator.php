<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Google Sitemap XML Creator, crawls a Website and writes a Sitemap XML File.
 *
 *	Copyright (c) 2007-2025 Christian Würker (ceusmedia.de)
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
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\Net\Site;

use CeusMedia\Common\FS\File\Block\Writer as BlockFileWriter;
use CeusMedia\Common\FS\File\Writer as FileWriter;

/**
 *	Google Sitemap XML Creator, crawls a Website and writes a Sitemap XML File.
 *	@category		Library
 *	@package		CeusMedia_Common_Net_Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2025 Christian Würker
 *	@license		https://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class MapCreator
{
	/**	@var		Crawler			$crawler	Instance of Site Crawler */
	protected Crawler $crawler;

	/**	@var		array			$errors		List of Errors */
	protected array $errors			= [];

	protected int $depth;

	protected array $links			= [];

	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$depth			Number of Links followed in a Row
	 *	@return		void
	 */
	public function __construct( int $depth = 10 )
	{
		$this->depth	= $depth;
	}

	/**
	 *	Crawls a Website, writes Sitemap XML File, logs Errors and URLs and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string			$url			URL of Website
	 *	@param		string			$sitemapUri		File Name of Sitemap XML File
	 *	@param		string|NULL		$errorsLogUri	File Name of Error Log File
	 *	@param		string|NULL		$urlListUri		File Name of URL Log File
	 *	@param		boolean			$verbose		Flag: show crawled URLs
	 *	@return		int
	 */
	public function createSitemap( string $url, string $sitemapUri, ?string $errorsLogUri = NULL, ?string $urlListUri = NULL, bool $verbose = FALSE ): int
	{
		$crawler	= new Crawler( $url, $this->depth );
		$crawler->crawl( $url, FALSE, $verbose );
		$this->errors	= $crawler->getErrors();
		$this->links	= $crawler->getLinks();
		$list	= [];
		foreach( $this->links as $link )
			$list[]	= $link['url'];
		$writtenBytes	= MapWriter::save( $sitemapUri, $list );
		if( $errorsLogUri ){
			@unlink( $errorsLogUri );
			if( count( $this->errors ) )
				$this->saveErrors( $errorsLogUri );
		}
		if( $urlListUri )
			$this->saveUrls( $urlListUri );
		return $writtenBytes;
	}

	/**
	 *	Returns List of Errors from last Sitemap Creation.
	 *	@access		public
	 *	@return		array
	 */
	public function getErrors(): array
	{
		return $this->errors;
	}

	/**
	 *	Returns List of found URLs from last Sitemap Creation.
	 *	@access		public
	 *	@return		array
	 */
	public function getLinks(): array
	{
		return $this->links;
	}

	/**
	 *	Writes Errors to a Block Log File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$uri		File Name of Block Log File
	 *	@return		int
	 */
	public function saveErrors( string $uri ): int
	{
		$writer	= new BlockFileWriter( $uri );
		return $writer->writeBlocks( $this->errors );
	}

	/**
	 *	Writes found URLs to a List File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$uri		File Name of Block Log File
	 *	@return		int
	 */
	public function saveUrls( string $uri ): int
	{
		$list	= [];
		foreach( $this->links as $link )
			$list[]	= $link['url'];
		$writer	= new FileWriter( $uri );
		return $writer->writeArray( $list );
	}
}

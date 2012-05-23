<?php
/**
 *	Google Sitemap XML Creator, crawls a Web Site and writes a Sitemap XML File.
 *
 *	Copyright (c) 2007-2012 Christian Würker (ceusmedia.com)
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
 *	@package		Net.Site
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.12.2006
 *	@version		$Id$
 */
/**
 *	Google Sitemap XML Creator, crawls a Web Site and writes a Sitemap XML File.
 *	@category		cmClasses
 *	@package		Net.Site
 *	@uses			Net_Site_MapapWriter
 *	@uses			Net_Site_Crawler
 *	@uses			File_Block_Writer
 *	@uses			File_Reader
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2012 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.12.2006
 *	@version		$Id$
 */
class Net_Site_MapCreator
{
	/**	@var		Net_Site_Crawler	$crawler	Instance of Site Crawler */
	protected $crawler;
	/**	@var		array				$errors		List of Errors */
	protected $errors					= array();
	
	/**
	 *	Constructor.
	 *	@access		public
	 *	@param		int			$depth			Number of Links followed in a Row
	 *	@return		void
	 */
	public function __construct( $depth = 10 )
	{
		$this->crawler	= new Net_Site_Crawler( $depth );
	}

	/**
	 *	Crawls a Web Site, writes Sitemap XML File, logs Errors and URLs and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$url			URL of Web Site
	 *	@param		string		$sitemapUri		File Name of Sitemap XML File
	 *	@param		string		$errorsLogUri	File Name of Error Log File
	 *	@param		string		$urlLogUri		File Name of URL Log File
	 *	@return		int
	 */
	public function createSitemap( $url, $sitemapUri, $errorsLogUri = NULL, $urlListUri = NULL )
	{
		$this->crawler->crawl( $url, FALSE, TRUE );
		$this->errors	= $this->crawler->getErrors();
		$this->urls		= array_keys( $this->crawler->getLinks() );
		$writtenBytes	= Net_Site_MapWriter::save( $sitemapUri, $this->urls );
		if( $errorsLogUri )
		{
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
	public function getErrors()
	{
		return $this->errors();
	}

	/**
	 *	Returns List of found URLs from last Sitemap Creation.
	 *	@access		public
	 *	@return		array
	 */
	public function getUrls()
	{
		return $this->urls();
	}

	/**
	 *	Writes Errors to a Block Log File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$uri		File Name of Block Log File
	 *	@return		int
	 */
	public function saveErrors( $uri )
	{
		$writer	= new File_Block_Writer( $uri );
		return $writer->writeBlocks( $this->errors );
	}
	
	/**
	 *	Writes found URLs to a List File and returns Number of written Bytes.
	 *	@access		public
	 *	@param		string		$uri		File Name of Block Log File
	 *	@return		int
	 */
	public function saveUrls( $uri )
	{
		$list	= new File_Writer( $uri );
		$list->writeArray( $this->urls );
	}
}
?>
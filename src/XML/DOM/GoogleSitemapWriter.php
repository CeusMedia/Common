<?php /** @noinspection PhpMultipleClassDeclarationsInspection */

/**
 *	Builds and writes Google Sitemap.
 *
 *	Copyright (c) 2007-2022 Christian Würker (ceusmedia.de)
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
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */

namespace CeusMedia\Common\XML\DOM;

use CeusMedia\Common\FS\File\Writer as FileWriter;
use Exception;

/**
 *	Builds and writes Google Sitemap.
 *	@category		Library
 *	@package		CeusMedia_Common_XML_DOM
 *	@author			Christian Würker <christian.wuerker@ceusmedia.de>
 *	@copyright		2007-2022 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			https://github.com/CeusMedia/Common
 */
class GoogleSitemapWriter
{
	/**	@var	array		$list		List of URLs */
	protected $list	= array();

	/**
	 *	Adds another Link to Sitemap.
	 *	@access		public
	 *	@param		string		$link		Link to add to Sitemap
	 *	@return		void
	 */
	public function addLink( string $link )
	{
		$this->list[]	= $link;
	}

	/**
	 *	Builds and write XML of Sitemap.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML Sitemap File
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		int
	 *	@throws		Exception
	 */
	public function write( string $fileName = 'sitemap.xml', string $baseUrl = '' ): int
	{
		return self::writeSitemap( $this->list, $fileName, $baseUrl );
	}

	/**
	 *	Builds and write XML of Sitemap.
	 *	@access		public
	 *	@static
	 *	@param		array		$links		List of Sitemap Link
	 *	@param		string		$fileName	File Name of XML Sitemap File
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		int
	 *	@throws		Exception
	 */
	public static function writeSitemap( array $links, string $fileName = 'sitemap.xml', string $baseUrl = '' ): int
	{
		$xml	= GoogleSitemapBuilder::buildSitemap( $links, $baseUrl );
		$file	= new FileWriter( $fileName );
		return $file->writeString( $xml );
	}
}

<?php
/**
 *	Builds and writes Google Sitemap.
 *
 *	Copyright (c) 2008 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.02.2008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.dom.GoogleSitemapBuilder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Builds and writes Google Sitemap.
 *	@package		xml.dom
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.02.2008
 *	@version		0.1
 */
class XML_DOM_GoogleSitemapWriter
{
	/**	@var	array		$list		List of URLs */
	protected $list	= array();

	/**
	 *	Adds another Link to Sitemap.
	 *	@access		public
	 *	@param		string		$link		Link to add to Sitemap
	 *	@return		void
	 */
	public function addLink( $link )
	{
		$this->list[]	= $link;
	}

	/**
	 *	Builds and write XML of Sitemap.
	 *	@access		public
	 *	@param		string		$fileName	File Name of XML Sitemap File
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		bool
	 */
	public function write( $fileName = "sitemap.xml", $baseUrl = "" )
	{
		return $this->writeSitemap( $this->list, $fileName, $baseUrl );
	}

	/**
	 *	Builds and write XML of Sitemap.
	 *	@access		public
	 *	@param		string		$links		List of Sitemap Link
	 *	@param		string		$fileName	File Name of XML Sitemap File
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		bool
	 */
	public static function writeSitemap( $links, $fileName = "sitemap.xml", $baseUrl = "" )
	{
		$xml	= XML_DOM_GoogleSitemapBuilder::buildSitemap( $links, $baseUrl );
		$file	= new File_Writer( $fileName );
		return $file->writeString( $xml );
	}
}
?>
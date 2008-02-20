<?php
import( 'de.ceus-media.xml.dom.GoogleSitemapBuilder' );
import( 'de.ceus-media.file.Writer' );
/**
 *	Builds and writes Google Sitemap.
 *	@package		xml.dom
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
/**
 *	Builds and writes Google Sitemap.
 *	@package		xml.dom
 *	@uses			XML_DOM_GoogleSitemapBuilder
 *	@uses			File_Writer
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
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
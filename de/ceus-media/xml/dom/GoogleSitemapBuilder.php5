<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Builds and writes Google Sitemap.
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
/**
 *	Builds and writes Google Sitemap.
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			14.02.2008
 *	@version		0.1
 */
class XML_DOM_GoogleSitemapBuilder
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
	 *	Builds and return XML of Sitemap.
	 *	@access		public
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		bool
	 */
	public function build( $baseUrl )
	{
		return $this->buildSitemap( $this->list, $baseUrl );
	}

	/**
	 *	Builds and return XML of Sitemap.
	 *	@access		public
	 *	@param		string		$links		List of Sitemap Link
	 *	@param		string		$baseUrl	Basic URL to add to every Link
	 *	@return		bool
	 */
	public static function buildSitemap( $links, $baseUrl = "" )
	{
		$root	= new XML_DOM_Node( "urlset" );
		$root->setAttribute( 'xmlns', "http://www.google.com/schemas/sitemap/0.84" );
		foreach( $links as $link )
		{
			$child	=& new XML_DOM_Node( "url" );
			$loc	=& new XML_DOM_Node( "loc", $baseUrl.$link );
			$child->addChild( $loc );
			$root->addChild( $child );
		}
		$builder	= new XML_DOM_Builder();
		return $builder->build( $root );
	}
}
?>
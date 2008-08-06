<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Google Sitemap XML Builder.
 *	@package		net.site
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 */
/**
 *	Builds Sitemap XML File for Google.
 *	@package		net.site
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@since			10.12.2006
 *	@version		0.2
 */
class Net_Site_MapBuilder
{
	/**
	 *	Builds Sitemap XML for List of URLs.
	 *	@access		public
	 *	@param		array		$urls			List of URLs
	 *	@return		string
	 */
	public static function build( $urls )
	{
		$set	= new XML_DOM_Node( "urlset" );
		$set->setAttribute( 'xmlns', "http://www.google.com/schemas/sitemap/0.84" );
		foreach( $urls as $url )
		{
			$node	=& new XML_DOM_Node( "url" );
			$child	=& new XML_DOM_Node( "loc", $url );
			$node->addChild( $child );
			$set->addChild( $node );
		}
		$xb	= new XML_DOM_Builder();
		return $xb->build( $set );
	}
}
?>
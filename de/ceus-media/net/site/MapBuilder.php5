<?php
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Google Sitemap XML Builder.
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
 *	@package		net.site
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			10.12.2006
 *	@version		0.2
 */
/**
 *	Builds Sitemap XML File for Google.
 *	@package		net.site
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <Christian.Wuerker@CeuS-Media.de>
 *	@copyright		2008 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
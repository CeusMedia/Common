<?php
/**
 *	Builds and writes Google Sitemap.
 *
 *	Copyright (c) 2007-2009 Christian Würker (ceus-media.de)
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
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
 *	@since			14.02.2008
 *	@version		0.1
 */
import( 'de.ceus-media.xml.dom.Node' );
import( 'de.ceus-media.xml.dom.Builder' );
/**
 *	Builds and writes Google Sitemap.
 *	@category		cmClasses
 *	@package		xml.dom
 *	@uses			XML_DOM_Node
 *	@uses			XML_DOM_Builder
 *	@author			Christian Würker <christian.wuerker@ceus-media.de>
 *	@copyright		2007-2009 Christian Würker
 *	@license		http://www.gnu.org/licenses/gpl-3.0.txt GPL 3
 *	@link			http://code.google.com/p/cmclasses/
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
	 *	@static
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